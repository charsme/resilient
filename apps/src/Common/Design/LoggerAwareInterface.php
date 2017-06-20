<?php

namespace App\Common\Design;

use Psr\Log\LoggerAwareInterface as BaseInterface;
use Psr\Log\LoggerInterface;

interface LoggerAwareInterface extends BaseInterface
{
    public function setLogger(LoggerInterface $logger):LoggerAwareInterface;

    public function getLogger():LoggerInterface;
}
