<?php
namespace App\Container;

use Interop\Container\ContainerInterface;
use DI\Container;
use Invoker\Invoker;
use Slim\Router;
use Slim\Handlers\Error;
use Slim\Handlers\PhpError;
use Slim\Handlers\NotFound;
use Slim\Handlers\NotAllowed;
use Invoker\ParameterResolver\AssociativeArrayResolver;
use Invoker\ParameterResolver\DefaultValueResolver;
use Invoker\ParameterResolver\ResolverChain;
use Invoker\ParameterResolver\Container\TypeHintContainerResolver;
use App\Common\CallableResolver;
use App\Common\ControllerInvoker;
use Slim\Http\Headers;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Http\Environment;
use Doctrine\Common\Cache\ApcuCache;
use Monolog\Processor\UidProcessor;
use Monolog\Processor\GitProcessor;
use Monolog\Processor\WebProcessor;
use Monolog\Processor\MemoryUsageProcessor;

$config = require __DIR__ . '/config.php';

$config['settings'] += [
    'httpVersion' => '1.1',
    'responseChunkSize' => 4096,
    'outputBuffering' => 'append',
    'determineRouteBeforeAppMiddleware' => false,
    'displayErrorDetails' => false,
    'addContentLengthHeader' => true,
    'routerCacheFile' => false,
];

$config += [
    Router::class => \DI\object(Router::class)->method('setCacheFile', $config['settings']['routerCacheFile']),
    'router' => \DI\get(Router::class),
    'errorHandler' => \DI\object(Error::class)->constructor($config['settings']['displayErrorDetails']),
    'phpErrorHandler' => \DI\object(PhpError::class)->constructor($config['settings']['displayErrorDetails']),
    'notFoundHandler' => \DI\object(NotFound::class),
    'notAllowedHandler' => \DI\object(NotAllowed::class),
    'environment' => function () {
        return new Environment($_SERVER);
    },
    'request' => function (ContainerInterface $c) {
        return Request::createFromEnvironment($c->get('environment'));
    },
    'response' => function (ContainerInterface $c) {
        $headers = new Headers(['Content-Type' => 'text/html; charset=UTF-8']);
        $response = new Response(200, $headers);
        return $response->withProtocolVersion($c->get('settings')['httpVersion']);
    },
    'foundHandler' => \DI\object(ControllerInvoker::class)->constructor(\DI\get('foundHandler.invoker')),
    'foundHandler.invoker' => function (ContainerInterface $c) {
        $resolvers = [
            new AssociativeArrayResolver,
            new TypeHintContainerResolver($c),
            // Then fall back on parameters default values for optional route parameters
            new DefaultValueResolver(),
        ];
        return new Invoker(new ResolverChain($resolvers), $c);
    },
    'callableResolver' => \DI\object(CallableResolver::class),
    // Aliases
    ContainerInterface::class => \DI\get(Container::class),

    UidProcessor::class => \DI\object(UidProcessor::class),
    GitProcessor::class => \DI\object(GitProcessor::class),
    WebProcessor::class => \DI\object(WebProcessor::class),
    MemoryUsageProcessor::class => \DI\object(MemoryUsageProcessor::class),

    PhpConsole\Connector::class => function () use ($config) {
        $phpConsole = PhpConsole\Handler::getInstance();
        $phpConsole->start();
        $phpConsole->getConnector()->setPassword($config['config']['log']['password']);
        $phpConsole->getConnector()->setSourcesBasePath(getenv('DOCUMENT_ROOT'));

        return $phpConsole->getConnector();
    }
];

$builder = new \DI\ContainerBuilder();

$builder->addDefinitions($config);

if ($config['config']['mode'] == 'production') {
    $cache = new ApcuCache;
    $cache->setNamespace($config['config']['appName']);

    $builder->setDefinitionCache($cache);
    $builder->writeProxiesToFile(true, $config['settings']['proxiesFolder']);
}

return $builder->build();
