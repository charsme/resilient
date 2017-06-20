<?php

return [
    'driver' => 'mongodb',
    'host' => getenv('MONGODB_HOST') ?: '127.0.0.1',
    'port' => getenv('MONGODB_PORT') ?: '27017',
    'database' => getenv('MONGODB_NAME') ?: 'labs',
    'username' => getenv('MONGODB_USERNAME') ?: 'labs',
    'password' => getenv('MONGODB_PASSWORD') ?: 'secret',
    'prefix' => getenv('MONGODB_PREFIX') ?: '',
    'charset' => getenv('MONGODB_CHARSET') ?: 'latin1',
    'collation' => getenv('MONGODB_COLLATION') ?: 'latin1_swedish_ci',
    'options' => [
        'database' => getenv('MONGODB_NAME') ?: 'labs'
    ],
    'query_log' => getenv('MONGODB_QUERYLOG') ?: false
];
