<?php

// Define root path
defined('DS') ?: define('DS', DIRECTORY_SEPARATOR);
defined('ROOT') ?: define('ROOT', dirname(__DIR__));

// Load .env file
if (file_exists(ROOT . '/.env')) {
    $dotenv = new Dotenv\Dotenv(ROOT);
    $dotenv->load();
}

return [
    'app_name' => env('APP_NAME'),
    'app_url'  => env('APP_URL'),
    'debug'    => env('APP_DEBUG'),
    'help_url' => env('HELP_URL'),
    'displayErrorDetails' => env('APP_DEBUG'),
    'determineRouteBeforeAppMiddleware' => true,

    'view' => [
        'templates' => ROOT . '/resources/views',
        'cache'     => ROOT . '/resources/cache',
        'debug'     => env('APP_DEBUG'),
    ],

    'database' => [
        'driver'    => env('DB_CONNECTION'),
        'host'      => env('DB_HOST'),
        'port'      => env('DB_PORT'),
        'database'  => env('DB_DATABASE'),
        'username'  => env('DB_USERNAME'),
        'password'  => env('DB_PASSWORD'),
        'charset'   => env('DB_CHARSET', 'utf8'),
        'collation' => env('DB_COLLATION', 'utf8mb4_unicode_ci'),
        'prefix'    => env('DB_PREFIX'),
    ],

    'mail' => [
        'host' => env('MAIL_HOST'),
        'port' => env('MAIL_PORT'),
        'security' => env('MAIL_ENCRYPTION'),
        'from' => [
            'name'    => env('MAIL_FROM_NAME'),
            'address' => env('MAIL_FROM_ADDRESS'),
        ],
        'username' => env('MAIL_USERNAME'),
        'password' => env('MAIL_PASSWORD'),
    ],

    'redis' => [
        'host'     => env('REDIS_HOST', 'localhost'),
        'password' => env('REDIS_PASSWORD', null),
        'port'     => env('REDIS_PORT', 6379),
    ],

    'translations' => [
        'path'     => ROOT . '/resources/lang',
        'fallback' => 'en',
    ]
];
