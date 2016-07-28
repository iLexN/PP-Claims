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
        $result = $this->c['loginModule']->isUserExistByToken($args['token']);

        if (!$result) {
            throw new \Slim\Exception\NotFoundException($request, $response);
        }

        $pass = $request->getParsedBody()['pswd'];
        $pass2 = $request->getParsedBody()['pswd2'];

        if ($pass !== $pass2) {
            return $this->c['view']->render($response, 'ForgotSetPassword.success.html.twig', [
                'token'  => $this->c['CSRFHelper']->getToken($request),
                'result' => 'password need same',
            ]);
        }

        if (!$this->c['passwordModule']->isStrongPassword($pass)) {
            return $this->c['view']->render($response, 'ForgotSetPassword.success.html.twig', [
                'token'  => $this->c['CSRFHelper']->getToken($request),
                'result' => 'password not strong',
            ]);
        }

        $this->c['loginModule']->postNewPassword($pass, $args['token']);

        return $this->c['view']->render($response, 'ForgotSetPassword.success.html.twig', [
            'token'  => $this->c['CSRFHelper']->getToken($request),
            'result' => 'success',
        ]);
    }
}
