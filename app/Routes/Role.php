<?php

use App\Controllers\RoleController;
use App\Middleware\AuthMiddleware;

$app->group('/role', function () {
    // Index
    $this->get('', RoleController::class . ':index')
        ->setName('role.index')
    ;
    // Datatable
    $this->get('/datatable', RoleController::class . ':datatable')
        ->setName('role.datatable')
    ;
    // Create
    $this->get('/create', RoleController::class . ':create')
        ->setName('role.create')
    ;
    // Store
    $this->post('', RoleController::class . ':store')
        ->setName('role.store')
    ;

    $this->group('/{role:[0-9]+}', function () {
        // View
        $this->get('', RoleController::class.':view')
            ->setName('role.view')
        ;
        // Edit
        $this->get('/edit', RoleController::class . ':edit')
            ->setName('role.edit')
        ;
        // Update
        $this->put('', RoleController::class . ':update')
            ->setName('role.update')
        ;
        // Delete
        $this->delete('', RoleController::class . ':delete')
            ->setName('role.delete')

        ;
    });
})->add(AuthMiddleware::class);
