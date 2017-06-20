<?php

use Analogue\ORM\Analogue;
use Resilient\Design\RendererInterface;
use Resilient\TwigRenderer;
use Resilient\Twig\Extension\Slim;
use App\Common\Mixer;
use Psr\Cache\CacheItemPoolInterface;
use Cache\Adapter\Predis\PredisCachePool;
use Monolog\Processor\UidProcessor;
use Monolog\Processor\GitProcessor;
use Monolog\Processor\WebProcessor;
use Monolog\Processor\MemoryUsageProcessor;
use Monolog\Handler\SlackWebhookHandler;
use Psr\Log\LoggerInterface;
use Monolog\Handler\PHPConsoleHandler;

$container = $app->getContainer();

$config = $container->get('config');

/**
 * ORM Connection handling Dependency
 */

$container->set(
            Analogue::class,
            function () use ($config) {
                $analogue = new Analogue($config['database']['default']);
                
                if ($config['database']['morphMap']) {
                    $analogue->morphMap($config['database']['morphMap']);
                }

                return $analogue;
            }
        );

/**
 * Renderer Dependency
 */

$container->set(
            Slim::class,
            DI\object(Slim::class)
            ->constructor(DI\get('router'), $container->get('request')->geturi())
        );

$container->set(
            RendererInterface::class,
            DI\object(TwigRenderer::class)
            ->constructor(...array_values($config['view']))
            ->method('addExtension', DI\object(Mixer::class)->constructor($config['url']['cdn']))
            ->method('addExtension', DI\get(Slim::class))
            ->lazy()
        );

/**
 * psr 6 Cache Pool Dependency
 */

$container->set(
            Predis\Client::class,
            DI\object(Predis\Client::class)->constructor(...array_values($config['cache']))->lazy()
        );

$container->set(
            CacheItemPoolInterface::class,
            DI\object(PredisCachePool::class)->constructor(DI\get(Predis\Client::class))->lazy()
        );

/**
 * Uploader Dependency
 */

$container->set(
            Cloudinary\Uploader::class,
            function () use ($config) {
                \Cloudinary::config($config['upload']['cloudinary']);
                return new \Cloudinary\Uploader();
            }
        );

/**
 * Logger Dependency Dependency
 */

$container->set(
            SlackWebhookHandler::class,
            DI\Object(SlackWebhookHandler::class)
                ->constructor(...array_values($config['log']['slack']))
                ->lazy()
        );

$container->set(
            PHPConsoleHandler::class,
            DI\object(PHPConsoleHandler::class)
                ->constructor(
                    ['dumperDetectCallbacks' => true],
                    DI\get(PhpConsole\Connector::class)
                    )
                ->lazy()
        );

$container->set(
            LoggerInterface::class,
            DI\object(Monolog\Logger::class)->constructor($config['appName'])
                ->method('pushHandler', DI\get(SlackWebhookHandler::class))
                ->method('pushHandler', DI\get(PHPConsoleHandler::class))
                ->method('pushProcessor', DI\get(MemoryUsageProcessor::class))
                ->method('pushProcessor', DI\get(GitProcessor::class))
                ->method('pushProcessor', DI\get(WebProcessor::class))
                ->method('pushProcessor', DI\get(UidProcessor::class))
                ->lazy()
        );
