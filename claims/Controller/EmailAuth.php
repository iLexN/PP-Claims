<?php

namespace PP\claims\controller;

use PP\Module\LoginModule;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class EmailAuth
{
    /* @var $c \Slim\Container */
    protected $c;

    public function __construct(\Slim\Container $container)
    {
        $this->c = $container;
    }

    /**
     * Login Post action.
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param array                  $args
     *
     * @return ResponseInterface
     */
    public function action(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        $token = $args['token'];

        $this->c->logger->info('token', $args);

        $login = new LoginModule();

        if ($login->checkToken($token)) {
            $login->setLogin();

            return $response->withStatus(301)->withHeader('Location', $this->c->router->pathFor('Login-ed'));
        }

        $this->c->flash->addMessage('loginError', 'Login expired');

        return $response->withStatus(301)->withHeader('Location', $this->c->router->pathFor('Homepage'));
    }
}
