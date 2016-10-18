<?php

namespace PP\WebPortal\Controller\ForgotSetPassword;

use PP\WebPortal\AbstractClass\AbstractContainer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class Action extends AbstractContainer
{
    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param array                  $args
     *
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        if (!$this->userModule->isUserExistByToken($args['token'])) {
            throw new \Slim\Exception\NotFoundException($request, $response);
        }

        $pass = $request->getParsedBody()['pswd'];
        $pass2 = $request->getParsedBody()['pswd2'];

        if ($msg = $this->isInValid($pass, $pass2)) {
            return $this->view->render($response, 'ForgotSetPassword.success.html.twig', [
                'token'  => $this->csrfHelper->getToken($request),
                'result' => $msg,
            ]);
        }

        $this->c['userModule']->postNewPassword($pass, $args['token']);

        return $this->view->render($response, 'ForgotSetPassword.success.html.twig', [
            'token'  => $this->csrfHelper->getToken($request),
            'result' => 'success',
        ]);
    }

    private function isInValid($p1,$p2){
        $msg = false;

        if ($p1 !== $p2) {
            $msg = 'password need same';
        }
        if (!$this->c['passwordModule']->isStrongPassword($p1)) {
            $msg = 'password not strong';
        }
        return $msg;
    }
}
