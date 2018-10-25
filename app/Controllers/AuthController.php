<?php

namespace App\Controllers;

use Carbon\Carbon;
use Slim\Http\{
    Response,
    Request
};
use App\Validators\UserPasswordValidator;
use Dflydev\FigCookies\FigResponseCookies;
use Dflydev\FigCookies\SetCookie;

class AuthController extends Controller
{
    public function login(Request $request, Response $response): Response
    {
        if ($request->isGet()) {
            return $this->render($response, 'login.twig');
        }

        $username = $request->getParam('username', '');
        $password = $request->getParam('password', '');

        if ($this->auth->attempt($username, $password)) {
            $user = $this->auth->user();

            $remember = $request->getParam('remember', false);

            if ($remember) {
                if (empty($user->remember_token)) {
                    $user->generateRememberToken();
                }

                $cookie = SetCookie::create($this->auth->getRememberCookieName())
                    ->withValue($this->auth->getRememberCookieValue())
                    ->withExpires(new \DateTime(REMEMBER_TIME));

                $response = FigResponseCookies::set($response, $cookie);
            }

            return $response->withRedirect(
                $this->auth->getRedirectUrl() ?: $this->pathFor('home')
            );
        }

        $this->get('flash')->addMessage('error', __('The username or password you entered is incorrect.'));
        return $this->redirect($response, 'login');
    }

    public function logout(Request $request, Response $response): Response
    {
        if ($this->auth->check()) {
            $user = $this->auth->user();
            $user->removeRememberToken();
            
            $this->auth->logout();
        }

        $response = FigResponseCookies::expire($response, $this->auth->getRememberCookieName());
        return $this->redirect($response, 'home');
    }
}
