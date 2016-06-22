<?php

namespace PP\WebPortal\Controller;

use PP\WebPortal\AbstractClass\AbstractContainer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class Logined extends AbstractContainer
{
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
        return $this->c['view']->render($response, 'logined.html.twig', [
            'sysMsg'  => 'Logined',
            'User'    => $this->c['user'],
            'Polices' => $this->c['policyModule']->getPolices(),
        ]);
    }
}
