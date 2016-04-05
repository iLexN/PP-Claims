<?php

namespace PP\claims\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Logined
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
     * Login-ed Page.
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param array                  $args
     *
     * @return ResponseInterface
     */
    public function action(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        return $this->c['view']->render($response, 'logined.html.twig', [
            'sysMsg' => 'Logined',
            'User' => $this->c['user'],
        ]);
    }
}
