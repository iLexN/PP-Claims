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

        $result = $this->userModule->userForgotUsername($data);

        return $response->write(json_encode($this->httpHelper->result));
    }
}