<?php

namespace PP\Middleware;

/**
 * auto redirect when user already login.
 */
class AuthCheckLoggedMiddleware
{
    /* @var $c \Slim\Container */
    protected $c;

    public function __construct(\Slim\Container $container)
    {
        $this->c = $container;
    }

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
        $loginModule = new \PP\Module\LoginModule();

        if ($loginModule->isLogined()) {
            return $response->withStatus(301)
                ->withHeader('Location', $this->c->router->pathFor('Login-ed'));
        }

        $response = $next($request, $response);

        return $response;
    }
}
