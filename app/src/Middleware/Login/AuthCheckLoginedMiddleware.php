<?php

namespace PP\WebPortal\Middleware\Login;

use PP\WebPortal\AbstractClass\AbstractContainer;

/**
 * auto redirect when user already login.
 */
final class AuthCheckLoginedMiddleware extends AbstractContainer
{
    /**
     * auto redirect when user already login.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request  PSR7 request
     * @param \Psr\Http\Message\ResponseInterface      $response PSR7 response
     * @param callable                                 $next     Next middleware
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke($request, $response, $next)
    {
        if ( $this->loginModule->isLogined()) {
            return $response->withStatus(301)
                ->withHeader('Location', $this->c['router']->pathFor('Login-ed'));
        }

        return $next($request, $response);
    }
}
