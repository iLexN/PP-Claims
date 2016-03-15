<?php

namespace PP\claims\controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Logined
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

        //print_r($_SESSION);

        //print_r($this->c->user);

        return $this->c->view->render($response, 'logined.html.twig', [
            'sysMsg' => 'Logined',
            'User'   => $this->c->user,
        ]);
    }
}
