<?php

namespace PP\claims\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class HomePageAction
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
    public function action(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        $email = $request->getParsedBody()['email'];
        $this->c['logger']->info('email', ['email' => $email]);

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
        return $this->c['loginModule']->login($email);
    }

    private function genLoginEmailToUser()
    {
        $this->c['loginModule']->genToken();
        $mailbody = $this->c['view']->fetch('email/login-email.twig', [
                'User' => $this->c['loginModule']->user,
        ]);

        echo $mailbody;
        //todo : send mail
    }
}
