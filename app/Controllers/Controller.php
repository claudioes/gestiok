<?php
namespace App\Controllers;

use Psr\Container\ContainerInterface;
use Slim\Http\Response;
use Slim\Http\Request;
use Slim\Http\Stream;
use App\Models\User;
use App\Mailer\Mailer;
use App\Mailer\Contracts\MailableContract;

abstract class Controller
{
    /** 
     * Container
     * 
     * @var Psr\Container\ContainerInterface 
     * */
    protected $container;

    /** 
     * User
     * 
     * @var \App\Models\User 
     */
    protected $user;

    /** 
     * View
     * 
     * @var Slim\Views\Twig 
     */
    protected $view;

    /** 
     * Router
     * 
     * @var Slim\Router 
     */
    protected $router;

    /** 
     * Logger
     * 
     * @var Monolog\Logger 
     */
    protected $logger;

    /**
     * Auth
     *
     * @var App\Auth\Auth
     */
    protected $auth;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->view      = $container->view;
        $this->router    = $container->router;
        $this->logger    = $container->logger;
        $this->auth      = $container->auth;
        $this->user      = $this->auth->user();
    }

    /**
     * Get from container
     * 
     * @param string $name Name
     * 
     * @return mixed
     */
    public function get(string $name)
    {
        return $this->container->get($name);
    }

    /**
     * Redirect
     * 
     * @param \Slim\Http\Response $response    Response
     * @param string              $route       Route name
     * @param array               $data        Data
     * @param array               $queryParams Query Params
     * 
     * @return Slim\Http\Response
     */
    protected function redirect(Response $response, string $route, array $data = [], array $queryParams = []): Response
    {
        return $response->withRedirect($this->router->pathFor($route, $data, $queryParams));
    }

    /**
     * Render
     * 
     * @param Response $response Response
     * @param string   $template Template
     * @param array    $args     Arguments
     * 
     * @return Response
     */
    protected function render(Response $response, string $template, array $args = []): Response
    {
        return $this->view->render($response, $template, $args);
    }

    /**
     * Shows not found page
     * 
     * @param \Slim\Http\Request  $request  Request
     * @param \Slim\Http\Response $response Response
     * 
     * @return Response
     */
    protected function notFound(Request $request, Response $response): Response
    {
        if ($request->isXhr()) {
            return $response->withStatus(404);
        }

        return $this->view->render($response, 'layouts/not_found.twig');
    }

    /**
     * Authorize a given action for the current user
     * 
     * @param string $ability Ability
     * @param [type] $model   Model
     * 
     * @return void
     * 
     * @throws \App\Exceptions\AuthorizationException
     */
    protected function authorize(string $ability, $model = null)
    {
        if (is_null($model)) {
            if (! $this->user->hasPermission($ability)) {
                throw new \App\Exceptions\AuthorizationException;
            }
        } else {
            if ($this->user->cannot($ability, $model)) {
                throw new \App\Exceptions\AuthorizationException;
            }
        }
    }

    /**
     * Returns the path for a route name
     * 
     * @param string $route       Route name
     * @param array  $args        Arguments
     * @param array  $queryParams Query params
     * 
     * @return string
     */
    protected function pathFor(string $route, array $args = [], array $queryParams = []) : string
    {
        return $this->router->pathFor($route, $args, $queryParams);
    }

    /**
     * Send mail
     * 
     * @param \App\Models\User                       $user     User
     * @param \App\Mailer\Contracts\MailableContract $mailable Mailable
     * 
     * @return void
     */
    protected function send(User $user, MailableContract $mailable)
    {
        try {
            $user->send($mailable);
        } catch (\Throwable $t) {
            $this->logger->error(
                $t->getMessage(),
                compact(
                    'user',
                    'mailable'
                )
            );
        }
    }

    /**
     * Returns a response with inline file
     * 
     * @param \Slim\Http\Request  $request  Request
     * @param \Slim\Http\Response $response Response
     * @param string              $path     File path
     * @param string              $rename   File name (optional)
     * 
     * @return \Slim\Http\Response
     */
    protected function responseInline(Request $request, Response $response, string $path, string $rename = null): Response
    {
        if (!file_exists($path)) {
            return $this->notFound($request, $response);
        }

        if (!$rename) {
            $rename = pathinfo($path, PATHINFO_BASENAME);
        }

        $stream = new Stream(fopen($path, 'rb'));

        return $response->withHeader('Content-Type', mime_content_type($path))
            ->withHeader('Content-Transfer-Encoding', 'binary')
            ->withHeader('Content-Disposition', 'inline; filename="' . $rename . '"')
            ->withHeader('Cache-Control', 'max-age=60, public')
            ->withBody($stream);
    }

    /**
     * Returns a response with download file
     * 
     * @param \Slim\Http\Request  $request  Request
     * @param \Slim\Http\Response $response Response
     * @param string              $path     File path
     * @param string              $rename   File name (optional)
     * 
     * @return \Slim\Http\Response
     */
    protected function responseDownload(Request $request, Response $response, string $path, string $rename = null): Response
    {
        if (!file_exists($path)) {
            return $this->notFound($request, $response);
        }

        if (!$rename) {
            $rename = pathinfo($path, PATHINFO_BASENAME);
        }

        $stream = new Stream(fopen($path, 'rb'));

        return $response->withHeader('Content-Type', mime_content_type($path))
            ->withHeader('Content-Transfer-Encoding', 'binary')
            ->withHeader('Content-Disposition', 'attachment; filename="' . $rename . '"')
            ->withHeader('Cache-Control', 'max-age=60, public')
            ->withBody($stream);
    }
}
