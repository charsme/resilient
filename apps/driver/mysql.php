<?php

return [
    'driver' => 'mysql',
    'host' => getenv('MYSQL_HOST') ?: '127.0.0.1',
    'port' => getenv('MYSQL_PORT') ?: '3306',
    'database' => getenv('MYSQL_NAME') ?: 'labs',
    'username' => getenv('MYSQL_USERNAME') ?: 'labs',
    'password' => getenv('MYSQL_PASSWORD') ?: 'secret',
    'prefix' => getenv('MYSQL_PREFIX') ?: '',
    'charset' => getenv('MYSQL_CHARSET') ?: 'latin1',
    'collation' => getenv('MYSQL_COLLATION') ?: 'latin1_swedish_ci',
    'query_log' => getenv('MYSQL_QUERYLOG') ?: false
];
