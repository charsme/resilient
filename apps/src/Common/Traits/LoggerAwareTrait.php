<?php

namespace App\Common\Traits;

use App\Common\Design\LoggerAwareInterface;
use Psr\Log\LoggerInterface;

trait LoggerAwareTrait
{
    protected $logger;

    public function setLogger(LoggerInterface $logger):LoggerAwareInterface
    {
        $this->logger = $logger;
        return $this;
    }

    public function getLogger():LoggerInterface
    {
        return $this->logger;
    }
}
