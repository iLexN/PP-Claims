<?php

namespace PP\claims\controller;

use PP\Module\LoginModule;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Homepage
{
    /* @var $c \Slim\Container */
    protected $c;

    public function __construct(\Slim\Container $container)
    {
        $this->c = $container;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param array                  $args
     *
     * @return ResponseInterface
     */
    public function index(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        $nameKey = $this->c->csrf->getTokenNameKey();
        $valueKey = $this->c->csrf->getTokenValueKey();
        $name = $request->getAttribute($nameKey);
        $value = $request->getAttribute($valueKey);

        return $this->c->view->render($response, 'homepage.html.twig', [
            'token' => [
                'nameKey'  => $nameKey,
                'name'     => $name,
                'valueKey' => $valueKey,
                'value'    => $value,
            ],
            'flash' => $this->c->flash->getMessages(),
        ]);
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
        $email = $request->getParsedBody()['email'];
        $this->c->logger->info('email', ['email' => $email]);

        $login = new LoginModule();
        if ($user = $login->login($email)) {
            $login->genToken();
            $mailbody = $this->c->view->fetch('email/login-email.twig', [
                'User' => $user,
            ]);

            echo $mailbody;
            //todo : send mail
        } else {
            // todo : all api check user
        }

        return $this->c->view->render($response, 'homepage-login-success.html.twig', [
            'sysMsg' => 'Not a user',
        ]);
    }
}
