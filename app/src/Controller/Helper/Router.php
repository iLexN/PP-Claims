<?php

namespace PP\WebPortal\Controller\Helper;

use PP\WebPortal\AbstractClass\AbstractContainer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class Router extends AbstractContainer
{
    /**
     * Router help.
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
