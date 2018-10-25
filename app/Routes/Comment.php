<?php

use App\Controllers\CommentController;
use App\Middleware\AuthMiddleware;

$app->group('/comment/{model_type}/{model_id:[0-9]+}', function () {
    // Index
    $this->get('', CommentController::class.':index')
        ->setName('comment.index')
    ;
    // Store
    $this->post('', CommentController::class.':store')
        ->setName('comment.store')
    ;
    // Delete
    $this->delete('/{comment:[0-9]+}', CommentController::class.':delete')
        ->setName('comment.delete')
    ;
})->add(AuthMiddleware::class);
