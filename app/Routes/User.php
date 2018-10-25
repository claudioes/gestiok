<?php

use App\Controllers\UserController;
use App\Middleware\AuthMiddleware;
use App\Middleware\ParamsPersistenceMiddleware;

$app->group('/user', function () {
    // Index
    $this->get('', UserController::class . ':index')
        ->setName('user.index')
        ->add(ParamsPersistenceMiddleware::class);
        
    // Create
    $this->get('/create', UserController::class . ':create')
        ->setName('user.create');

    // Store
    $this->post('', UserController::class . ':store')
        ->setName('user.store');

    $this->group('/{id:[0-9]+}', function () {
        // Edit
        $this->get('/edit', UserController::class . ':edit')
            ->setName('user.edit');

        // Update
        $this->put('', UserController::class . ':update')
            ->setName('user.update');

        // Delete
        $this->delete('', UserController::class . ':delete')
            ->setName('user.delete');
    });
})->add(AuthMiddleware::class);
