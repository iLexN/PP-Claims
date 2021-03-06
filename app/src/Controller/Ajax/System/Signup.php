<?php

namespace PP\WebPortal\Controller\Ajax\System;

use PP\WebPortal\AbstractClass\AbstractContainer;
use Slim\Http\Request;
use Slim\Http\Response;

final class Signup extends AbstractContainer
{
    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param array                  $args
     *
     * @return ResponseInterface
     */
    public function __invoke(Request $request, Response $response, array $args)
    {
        $username = $request->getParsedBody()['singup_username'];
        $pass = $request->getParsedBody()['singup_new_password'];
        $pass2 = $request->getParsedBody()['singup_new_password2'];

        if ($msg = $this->helper->isPasswordInValid($pass, $pass2)) {
            return $response->withJson(['errors' => ['title' => $msg]]);
        }

        $result = $this->userModule->userSign([
            'user_name' => $username,
            'password'  => $pass,
        ], $this->loginModule->getSignUpID());

        if ($result) {
            $this->loginModule->setLogined(['id' => $this->loginModule->getSignUpID()]);
        }

        return $response->withJson($this->httpHelper->result);
    }
}
