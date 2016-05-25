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
        $email = $request->getParsedBody()['email'];
        $this->c['logger']->info('email', ['email' => $email]);

        if ($this->isUserExist($email)) {
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
    private function isUserExist($email)
    {
        return $this->c['loginModule']->isUserExist($email);
    }

    /**
     * action when user exist.
     */
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
