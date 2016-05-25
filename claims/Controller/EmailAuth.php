<?php

namespace PP\Claims\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class EmailAuth
{
    /**
     * @var \Slim\Container
     */
    protected $c;

    public function __construct(\Slim\Container $container)
    {
        $this->c = $container;
    }

    /**
     * Email Auth Check action.
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param array                  $args
     *
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        $this->c['logger']->info('token', $args);

        /* @var $loginModule \PP\Module\LoginModule */
        $loginModule = $this->c['loginModule'];

        if ($loginModule->checkToken($args['token'])) {
            $loginModule->setLogined();

            return $response->withStatus(301)->withHeader('Location', $this->c['router']->pathFor('Login-ed'));
        }

        $this->c['flash']->addMessage('loginError', 'Login expired');

        return $response->withStatus(301)->withHeader('Location', $this->c['router']->pathFor('Homepage'));
    }
}
