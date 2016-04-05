<?php

namespace PP\claims\Controller;

use PP\Module\LoginModule;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class HomePageAction
{
    /* @var $c \Slim\Container */
    protected $c;

    /* @var $loginModule \PP\Module\LoginModule */
    protected $loginModule;

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
        $email = $request->getParsedBody()['email'];
        $this->c['logger']->info('email', ['email' => $email]);

        $this->loginModule = new LoginModule();
        if ($this->checkLogin($email)) {
            $this->genLoginEmailToUser();
            $msg = 'success';
        } else {
            // todo : all api check user
            $msg = 'fail';
        }

        return $this->c['view']->render($response, 'homepage-login-success.html.twig', [
            'sysMsg' => $msg,
        ]);
    }

    /**
     * check email in db or not(already user?).
     *
     * @param string $email
     *
     * @return bool
     */
    private function checkLogin($email)
    {
        return $this->loginModule->login($email);
    }

    private function genLoginEmailToUser()
    {
        $this->loginModule->genToken();
        $mailbody = $this->c['view']->fetch('email/login-email.twig', [
                'User' => $this->loginModule->user,
        ]);

        echo $mailbody;
        //todo : send mail
    }
}
