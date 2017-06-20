<?php

$dotenv = new Dotenv\Dotenv(__DIR__ . '/../');
$dotenv->load();

$storage_dir = __DIR__ . '/../storage/';

return [
    'settings' => [
        'displayErrorDetails' => getenv('DEBUG') ?: false,
        'addContentLengthHeader' => true,
        'determineRouteBeforeAppMiddleware' => false,
        'routerCacheFile' => $storage_dir . 'router/router.cache',
        'proxiesFolder' => $storage_dir . 'proxies/',
    ],
    'config' => [
        'site' => [
            'title' => '',
            'descriptions' => '',
            'url' => getenv('BASE_URL') ?: '/',
            'cover' => ''
        ],
        'appName' => getenv('APP_NAME') ? :'labs',
        'baseUrl' => getenv('BASE_URL') ? :'http://labs.app/',
        'mode' => getenv('APP_MODE') ?: 'development',
        'database' => [
            'default' => require __DIR__ . '/driver/mysql.php',
            'sqlite' => [
                'driver'   => 'sqlite',
                'database' => $storage_dir . 'database.sqlite',
                'prefix'   => '',
            ],
            'morphMap' => []
        ],
        'cache' => [
            'connections' => require __DIR__ . '/driver/redis.php',
            'options' => [
                'prefix' => getenv('REDIS_PREFIX') ? :'labs:'
            ]
        ],
        'view' => [
            'path' => getenv('DOCUMENT_ROOT') . '/../resources/views/',
            'options' => [
                'cache' => $storage_dir . 'views/',
                'debug' => getenv('DEBUG') ?: false,
            ],
        ],
        'log' => [
            'password' => getenv('LOG_PASSWORD') ?: 'labs-log',
            'slack' => [
                'webhookUrl' => getenv('SLACK_WEBHOOK') ?: '',
                'channel' => getenv('SLACK_CHANNEL') ?: 'error',
                'username' => getenv('SLACK_BOT_NAME') ?: 'bot',
                'useAttachment' => true,
                'iconEmoji' => getenv('SLACK_EMOJI') ?: ':robot_face: ',
                'useShortAttachment' => true,
                'includeContextAndExtra' => true,
                'level' => \Monolog\Logger::WARNING
            ],
        ],
        'upload' => [
            'cloudinary' => [
                "cloud_name" => getenv('CLOUDINARY_CLOUD_NAME') ?: "",
                "api_key" => getenv('CLOUDINARY_API_KEY') ?: "",
                "api_secret" => getenv('CLOUDINARY_SECRET') ?: ""
            ],
            
            'local' => $storage_dir . 'upload/',
            'remote' => getenv('CLOUDINARY_REMOTE_DIR') ?: ''
        ],
        'url' => [
            'cdn' => getenv('CDN') ?: '',
        ]
    ]
];
