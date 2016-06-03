<?php

namespace PP\Claims\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Logout
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
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        $this->c['loginModule']->setLogout();

        $this->c['flash']->addMessage('loginError', 'Logout');

        return $response->withStatus(301)
                ->withHeader('Location', $this->c['router']->pathFor('Homepage'));
    }
}
