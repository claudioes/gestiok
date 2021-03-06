<?php

require_once __DIR__ . '/vendor/autoload.php';

$settings = require __DIR__ . '/app/settings.php';
$database = $settings['database'];

$capsule = new Illuminate\Database\Capsule\Manager;
$capsule->addConnection([
    'driver'    => $database['driver'],
    'host'      => $database['host'],
    'database'  => $database['database'],
    'username'  => $database['username'],
    'password'  => $database['password'],
    'charset'   => $database['charset'],
    'collation' => $database['collation'],
    'prefix'    => $database['prefix'],
]);

$capsule->setAsGlobal();
$capsule->bootEloquent();
$capsule->getConnection()->getSchemaBuilder()->defaultStringLength(190);

return [
    'paths' => [
        'migrations' => __DIR__ . '/database/migrations',
        'seeds' => __DIR__ . '/database/seeds',
    ],

    'migration_base_class' => App\Database\Migrations\Migration::class,

    'templates' => [
        'file' => __DIR__ . '/app/Database/Migrations/Migration.stub'
    ],

    'environments' => [
        'default_migration_table' => 'migrations',
        'default' => [
            'adapter' => $database['driver'],
            'host'    => $database['host'],
            'port'    => $database['port'],
            'name'    => $database['database'],
            'user'    => $database['username'],
            'pass'    => $database['password'],
        ]
    ]
];
