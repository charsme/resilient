<?php
namespace App\Common;

use Slim\Interfaces\CallableResolverInterface;

/**
 * Resolve middleware and route callables using PHP-DI.
 */
class CallableResolver implements CallableResolverInterface
{
    /**
     * @var \Invoker\CallableResolver
     */
    private $callableResolver;
    public function __construct(\Invoker\CallableResolver $callableResolver)
    {
        $this->callableResolver = $callableResolver;
    }
    /**
     * {@inheritdoc}
     */
    public function resolve($toResolve)
    {
        return $this->callableResolver->resolve($toResolve);
    }
}
