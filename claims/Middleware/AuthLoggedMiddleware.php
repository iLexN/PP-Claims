<?php

namespace PP\Middleware;

/**
 * go to homeage when login expired
 * get user info when user already login.
 */
class AuthLoggedMiddleware
{
    /* @var $c \Slim\Container */
    protected $c;

    public function __construct(\Slim\Container $container)
    {
        $this->c = $container;
    }

    /**
     * Example middleware invokable class.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request  PSR7 request
     * @param \Psr\Http\Message\ResponseInterface      $response PSR7 response
     * @param callable                                 $next     Next middleware
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke($request, $response, $next)
    {
        if (!isset($_SESSION['userLogin'])) {
            $this->c->flash->addMessage('loginError', 'Login expired');

            return $response->withStatus(301)
                ->withHeader('Location', $this->c->router->pathFor('Homepage'));
        }

        $login = new \PP\Module\LoginModule();
        $this->c['user'] = $login->getUser();

        $response = $next($request, $response);

        return $response;
    }
}
