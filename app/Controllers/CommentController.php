<?php

namespace App\Controllers;

use App\Models\Comment;
use Slim\Http\{
    Response,
    Request
};

class CommentController extends Controller
{
    private function getModel(string $type, string $id)
    {
        $class = '\\App\\Models\\' . ucfirst($type);
        $model = $class::findOrFail($id);

        return $model;
    }

    public function index(Request $request, Response $response, array $args): Response
    {
        $model = $this->getModel($args['model_type'], $args['model_id']);

        $comments = Comment::byModel($model)
            ->with('createdByUser')
            ->orderBy('created_at', 'asc')
            ->get()
        ;

        $html = $this->view->fetch('comment/index.twig', [
            'comments' => $comments,
            'model_type' => $args['model_type'],
            'model_id' => $args['model_id'],
        ]);

        return $response->withJson([
            'html' => $html,
            'count' => $comments->count()
        ]);
    }

    public function store(Request $request, Response $response, array $args): Response
    {
        $model = $this->getModel($args['model_type'], $args['model_id']);

        $comment = new Comment([
            'message' => $request->getParam('message')
        ]);

        $comment->setCreatedBy($this->user);

        $model->comments()->save($comment);

        return $response->withJson($comment);
    }
}
