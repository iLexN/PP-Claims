<?php

namespace PP\claims\Controller\Helper;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Router
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
     * Login Post action.
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param array                  $args
     *
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {

        /* @var $router \Slim\Router */
        $router = $this->c->router;

        //exclude route
        $router->removeNamedRoute('helperRouter');

        return $this->c['view']->render($response, 'helper/router.html.twig', [
            'router' => $router,
        ]);
    }
}
