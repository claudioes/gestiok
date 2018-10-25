<?php

namespace App\Middleware;

use Slim\{
    Http\Request,
    Http\Response,
    Router
};
use Dflydev\FigCookies\FigRequestCookies;
use App\Facades\Auth;

class AuthMiddleware
{
    /** @var Slim\Http\Router */
    protected $router;

    public function __construct($container)
    {
        $this->router = $container->router;
    }

    public function __invoke(Request $request, Response $response, callable $next): Response
    {
        $routeName = $request->getAttribute('route')->getName();

        if (Auth::check()) {
            $user = Auth::user();

            if ($user->password_is_expired && ! in_array($routeName, ['change_password', 'logout'])) {
                return $response->withRedirect($this->router->pathFor('change_password'));
            }

            if ($routeName == 'login') {
                return $response->withRedirect($this->router->pathFor('home'));
            }
        } else {
            $rememberCookie = FigRequestCookies::get($request, Auth::getRememberCookieName())->getValue();

            if ($rememberCookie && Auth::attemptRemember($rememberCookie)) {
                $response = $next($request, $response);
                return $response;
            }

            if ($routeName != 'login') {
                Auth::setRedirectUrl((string)$request->getUri());
                return $response->withRedirect($this->router->pathFor('login'));
            }
        }

        $response = $next($request, $response);
        return $response;
    }
}
