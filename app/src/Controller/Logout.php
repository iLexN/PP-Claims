<?php

namespace PP\WebPortal\Controller;

use PP\WebPortal\AbstractClass\AbstractContainer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class Logout extends AbstractContainer
{
    /**
     * Logout Page.
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param array                  $args
     *
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        $this->loginModule->setLogout();

        $this->flash->addMessage('loginError', 'Logout');

        return $response->withStatus(301)
                ->withHeader('Location', $this->c['router']->pathFor('Homepage'));
    }
}
