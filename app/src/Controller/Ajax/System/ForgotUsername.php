<?php

namespace PP\WebPortal\Controller\Ajax\System;

use PP\WebPortal\AbstractClass\AbstractContainer;
use Slim\Http\Request;
use Slim\Http\Response;

final class ForgotUsername extends AbstractContainer
{
    public function __invoke(Request $request, Response $response, array $args)
    {
        $data = $request->getParsedBody();

        $this->userModule->userForgotUsername($data);

        //return $response->write(json_encode($this->httpHelper->result));
        return $response->withJson($this->httpHelper->result);
    }
}
