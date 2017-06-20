<?php

namespace App\Common\Traits;

use Psr\Cache\CacheItemPoolInterface;
use App\Common\Design\CacheItemPoolAwareInterface;

/**
 * CacheItemPoolAwareInterface interface
 * To make it easier when the need to change container arise
 * complement some container have great autowirin, some have great inflector
 */
trait CacheItemPoolAwareTrait
{
    protected $cachePool;

    public function getCachePool():CacheItemPoolInterface
    {
        return $this->cachePool;
    }

    public function setCachePool(CacheItemPoolInterface $cachePool):CacheItemPoolAwareInterface
    {
        $this->cachePool = $cachePool;

        return $this;
    }
}
