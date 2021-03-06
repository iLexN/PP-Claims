<?php

namespace PP\WebPortal\Controller\Ajax\System;

use PP\WebPortal\AbstractClass\AbstractContainer;
use Slim\Http\Request;
use Slim\Http\Response;

final class ForgotUsername extends AbstractContainer
{
    public function __invoke(Request $request, Response $response, array $args)
    {
        $data = (array) $request->getParsedBody();

        $this->userModule->userForgotUsername($data);

        return $response->withJson($this->httpHelper->result);
    }
}
