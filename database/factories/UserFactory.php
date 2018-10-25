<?php

use Faker\Generator;

$factory->define(App\Models\User::class, function (Generator $faker) {
    return [
        'username' => $faker->userName,
        'firstname' => $faker->name,
        'is_active' => true,
        'is_admin' => false,
    ];
});

$factory->state(App\Models\User::class, 'admin', [
    'is_admin' => true,
]);

$factory->afterMaking(App\Models\User::class, function ($user, $faker) {
    $user->setPassword($user->username);
});