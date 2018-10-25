<?php

// Twig
$container['view'] = function ($c) {
    $settings = $c['settings'];

    $view = new Slim\Views\Twig($settings['view']['templates'], [
        'cache' => $settings['view']['cache'],
        'debug' => $settings['view']['debug'],
    ]);

    // Extensions

    $request = $c->get('request');

    $view->addExtension(new Slim\Views\TwigExtension(
        $c->get('router'),
        $request->getUri()
    ));
    $view->addExtension(new App\TwigExtensions\CsrfExtension(
        $c->get('csrf')
    ));
    $view->addExtension(new App\TwigExtensions\DebugExtension);
    $view->addExtension(new App\TwigExtensions\TranslationExtension($c->get('translator')));

    // Globals

    $env = $view->getEnvironment();
    
    $env->getExtension('Twig_Extension_Core')->setDateFormat(DATE_FORMAT, '%d days');

    $env->addGlobal('app_name', $settings['app_name']);
    $env->addGlobal('flash', $c->get('flash'));

    $auth = $c->get('auth');
    $env->addGlobal('auth', [
        'check' => $auth->check(),
        'user' => $auth->user(),
    ]);

    return $view;
};

// CSRF
$container['csrf'] = function ($c) {
    $guard = new Slim\Csrf\Guard;
    $guard->setPersistentTokenMode(true);
    $guard->setFailureCallable(function ($request, $response, $next) {
        if ($request->isXhr()) {
            return $response->withJson([
                'error' => 'Invalid token.'
            ], 400);
        }

        $request = $request->withAttribute("csrf_status", false);
        return $next($request, $response);
    });

    return $guard;
};

// Flash messages
$container['flash'] = function ($c) {
    if (session_status() === PHP_SESSION_ACTIVE) {
        return new Slim\Flash\Messages;
    }

    return null;
};

// Auth
$container['auth'] = function ($c) {
    return new App\Auth\Auth;
};

// Intervention (IMagick)
$container['image'] = function ($c) {
    return new Intervention\Image\ImageManager([
        'driver' => 'imagick'
    ]);
};

// Mail
$container['mailer'] = function ($c) {
    $settings = $c['settings'];

    if (empty($settings['mail']['host'])) {
        return null;
    }

    $transport = new Swift_SmtpTransport(
        $settings['mail']['host'],
        $settings['mail']['port'],
        $settings['mail']['security']
    );

    $transport->setUsername($settings['mail']['username']);
    $transport->setPassword($settings['mail']['password']);

    $view = new Slim\Views\Twig($settings['view']['templates']);
    $view->addExtension(new Slim\Views\TwigExtension(
        $c->get('router'),
        $settings['app_url']
    ));

    $queue = new \App\Queue\RedisQueue([
        'vendor' => 'predis',
        'host' => $settings['redis']['host'],
        'port' => $settings['redis']['port'],
        'password' => $settings['redis']['password'],
    ], 'emails');

    $mailer = new App\Mailer\Mailer(
        new Swift_Mailer($transport),
        $view,
        $queue
    );

    $mailer->alwaysFrom($settings['mail']['from']['address'], $settings['mail']['from']['name']);

    return $mailer;
};

// Monolog
$container['logger'] = function ($c) {
    $logger = new Monolog\Logger('app');

    $handler = new Monolog\Handler\StreamHandler(
        sprintf('%s/log/app_%s.log', ROOT, date('Y-m-d'))
    );

    $logger->pushHandler($handler);

    return $logger;
};

// Error handler
$container['errorHandler'] = function ($c) {
    $settings = $c['settings'];

    return new App\Handlers\ErrorHandler($c['logger'], $c['view'], $settings['displayErrorDetails']);
};

// Translator
$container['translator'] = function ($c) {
    $translator = new Symfony\Component\Translation\Translator('es');
    $translator->addLoader('array', new Symfony\Component\Translation\Loader\ArrayLoader());

    foreach (glob(ROOT . '/resources/lang/*.php') as $file) {
        list($locale, $domain) = explode('.', basename($file));
        $messages = require $file;

        $translator->addResource('array', $messages, $locale, $domain);
    }

    return $translator;
};

$container['queue'] = function ($c) {
    $settings = $c['settings']['redis'];
    
    return new \App\Queue\RedisQueue([
        'vendor' => 'predis',
        'host' => $settings['host'],
        'port' => $settings['port'],
        'password' => $settings['password'],
    ]);
};

$container->register(new \App\Services\EloquentServiceProvider);
$container->register(new \App\Services\CacheServiceProvider);

App\Facades\Facade::setFacadeContainer($container);
