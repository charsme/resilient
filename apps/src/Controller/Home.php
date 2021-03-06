<?php

namespace App\Controller;

use App\Common\Design\CacheItemPoolAwareInterface;
use App\Common\Traits\CacheItemPoolAwareTrait;
use Resilient\Design\RendererAwareInterface;
use Resilient\Traits\RendererAwareTrait;
use App\Common\Design\LoggerAwareInterface;
use App\Common\Traits\LoggerAwareTrait;
use Resilient\Design\RendererInterface;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Log\LoggerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Analogue\ORM\Analogue;
use Psr\Http\Message\ResponseInterface;

class Home implements LoggerAwareInterface, RendererAwareInterface, CacheItemPoolAwareInterface
{
    use CacheItemPoolAwareTrait, RendererAwareTrait, LoggerAwareTrait;

    protected $orm;

    public function __construct(RendererInterface $renderer, CacheItemPoolInterface $cachePool, LoggerInterface $logger, Analogue $orm)
    {
        $this->setCachePool($cachePool);
        $this->setRenderer($renderer);
        $this->setLogger($logger);

        $this->orm = $orm;
    }

    public function __invoke(ResponseInterface $response)
    {
        //mapping orm
        //$userMapper = $this->orm->mapper(Model\User::class);
        //dump($userMapper->get(["name"])->toArray());

        dump($this);

        return $this->getRenderer()->render($response, 'debug.twig', ['message' => __CLASS__]);
    }
}
