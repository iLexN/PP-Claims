<?php

namespace PP\WebPortal\Controller\Ajax\System;

use PP\WebPortal\AbstractClass\AbstractContainer;
use Slim\Http\Request;
use Slim\Http\Response;

final class ForgotPassword extends AbstractContainer
{
    public function __invoke(Request $request, Response $response, array $args)
    {
        $username = $request->getParsedBodyParam('forgotpassword_username');

        $this->userModule->userForgotPassword($username);

        //return $response->write(json_encode($this->httpHelper->result));
        return $response->withJson($this->httpHelper->result);
    }
}
