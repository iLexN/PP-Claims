<?php

namespace PP\WebPortal\Controller\HomePage;

use PP\WebPortal\AbstractClass\AbstractContainer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class Action extends AbstractContainer
{
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
        $result = $this->isUserExist((array) $request->getParsedBody());
        $this->c['logger']->info('result isUserExist', [$result]);

        if ($result) {
            $this->c['loginModule']->setLogined($result['data']);

            return $response->withStatus(301)
                    ->withHeader('Location', $this->c['router']->pathFor('Login-ed'));
        } else {
            $this->c['flash']->addMessage('loginError', 'Login Fail');

            return $response->withStatus(301)
                    ->withHeader('Location', $this->c['router']->pathFor('Homepage'));
        }
    }

    /**
     * check email in db or not(already user?).
     *
     * @param array $input
     *
     * @return bool
     */
    private function isUserExist($input)
    {
        return $this->c['loginModule']->isUserExist($input);
    }
}
