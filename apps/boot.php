<?php

require __DIR__ . '/../vendor/autoload.php';

session_start();
ini_set('memory_limit', '256M');
date_default_timezone_set('Asia/Jakarta');

$container = require __DIR__ . '/container.php';

$app = new \Slim\App($container);

require __DIR__ . '/dependencies.php';

require __DIR__ . '/middlewares.php';

require __DIR__ . '/routes.php';

$app->run();
