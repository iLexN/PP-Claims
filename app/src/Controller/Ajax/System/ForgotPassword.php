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

        $this->logger->info('useranme', $_POST);
        $this->logger->info('useranme', ['u'=>$username]);

        $result = $this->userModule->userForgotPassword($username);

        if ($result) {
        } else {
            $this->logger->info('login error', $this->httpHelper->getErrorMessages());
        }

        return $response->write(json_encode($this->httpHelper->result));
    }
}
