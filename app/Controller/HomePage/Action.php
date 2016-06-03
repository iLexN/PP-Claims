<?php

namespace PP\Claims\Controller\HomePage;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Action
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
