<?php

use App\Controllers\AttachmentController;
use App\Middleware\AuthMiddleware;

$app->group('/attachment/{model_type}/{model_id:[0-9]+}', function () {
    // Index
    $this->get('', AttachmentController::class.':index')
        ->setName('attachment.index')
    ;
    // Store
    $this->post('', AttachmentController::class.':store')
        ->setName('attachment.store')
    ;
    // Download
    $this->get('/{attachment}', AttachmentController::class.':download')
        ->setName('attachment.download')
    ;
    // Delete
    $this->delete('/{attachment}', AttachmentController::class.':delete')
        ->setName('attachment.delete')
    ;
})->add(AuthMiddleware::class);
