<?php

namespace App\Common;

use Invoker\InvokerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Interfaces\InvocationStrategyInterface;

class ControllerInvoker implements \Slim\Interfaces\InvocationStrategyInterface
{
    /**
     * @var InvokerInterface
     */
    private $invoker;
    public function __construct(InvokerInterface $invoker)
    {
        $this->invoker = $invoker;
    }
    /**
     * Invoke a route callable.
     *
     * @param callable               $callable       The callable to invoke using the strategy.
     * @param ServerRequestInterface $request        The request object.
     * @param ResponseInterface      $response       The response object.
     * @param array                  $routeArguments The route's placeholder arguments
     *
     * @return ResponseInterface|string The response from the callable.
     */
    public function __invoke(
        callable $callable,
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $routeArguments
    ) {
        // Inject the request and response by parameter name
        $parameters = [
            'request'  => $request,
            'response' => $response,
        ];
        //spread parameter and inject by name from router matched name
        $parameters += $routeArguments;
        return $this->invoker->call($callable, $parameters);
    }
}
