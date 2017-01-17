<?php

namespace PP\WebPortal\Middleware\Login;

use PP\WebPortal\AbstractClass\AbstractContainer;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * use for need login-ed page
 * go to homeage when login expired
 * get user info when user already login.
 */
final class AuthLoginedAreaMiddleware extends AbstractContainer
{
    public function __invoke(Request $request, Response $response, $next)
    {
        if (!$this->loginModule->isLogined()) {
            //$this->c['flash']->addMessage('loginError', 'Login expired');

            if ($request->isXhr()) {
                return $response;
            } else {
                return $response->withStatus(301)
                    ->withHeader('Location', $this->c['router']->pathFor('Homepage'));
            }
        }

        $this->loginModule->getUserByLoginSession();
        $this->view['User'] = $this->userModule->user;

        return $next($request, $response);
    }
}
