<?php

namespace App\Common\Design;

use \Psr\Cache\CacheItemPoolInterface;

/**
 * CacheItemPoolAwareInterface interface
 * To make it easier when the need to change container arise
 * complement some container have great autowirin, some have great inflector
 */
interface CacheItemPoolAwareInterface
{
    /**
     * getCachePool
     * to retrieve cachePool made for inflector consumption
     *
     * @return CacheItemPoolInterface
     */
    public function getCachePool():CacheItemPoolInterface;

    /**
     * setCachePool
     * to set cachePool made for inflector consumption
     *
     * @param CacheItemPoolInterface $cachePool
     * @return CacheItemPoolAwareInterface
     */
    public function setCachePool(CacheItemPoolInterface $cachePool):CacheItemPoolAwareInterface;
}
