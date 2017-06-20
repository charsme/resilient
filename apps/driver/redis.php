<?php

return [
    'scheme' => 'tcp',
    'host' => getenv('REDIS_HOST') ?: '192.168.0.253',
    'port' => getenv('REDIS_PORT') ?: 6379,
    'password' => getenv('REDIS_PASSWORD') ?: '',
];
