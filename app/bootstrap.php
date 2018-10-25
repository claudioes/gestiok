<?php

if (PHP_SAPI == 'cli-server') {
    // To help the built-in PHP dev server, check if the request was actually for
    // something which should probably be served as a static file
    $url  = parse_url($_SERVER['REQUEST_URI']);
    $file = __DIR__ . $url['path'];
    if (is_file($file)) {
        return false;
    }
}

require __DIR__ . '/../vendor/autoload.php';

session_cache_limiter(false); // NO delete
session_start();

// Constants
require __DIR__ . '/constants.php';

// Settings
$settings = require __DIR__ . '/settings.php';

// Instantiate the app
$app = new \Slim\App(['settings' => $settings]);
$container = $app->getContainer();

// Dependencies
require ROOT . '/app/dependencies.php';

// Middleware
require ROOT . '/app/middleware.php';

// Routes
require ROOT . '/app/routes.php';
