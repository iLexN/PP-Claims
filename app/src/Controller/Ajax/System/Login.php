<?php

namespace PP\WebPortal\Controller\Ajax\System;

use PP\WebPortal\AbstractClass\AbstractContainer;
use Slim\Http\Request;
use Slim\Http\Response;

final class Login extends AbstractContainer
{
    public function __invoke(Request $request, Response $response, array $args)
    {
        $result = $this->loginModule->isUserExist((array) $request->getParsedBody());

        if ($result) {
            $this->loginModule->setLogined($result['data']);
        } else {
            $this->logger->info('login error', $this->httpHelper->getErrorMessages());
        }

        //return $response->write(json_encode($this->httpHelper->result));
        return $response->withJson($this->httpHelper->result);
    }
}
