<?php

namespace PP\WebPortal\Controller\Ajax\System;

use PP\WebPortal\AbstractClass\AbstractContainer;
use Slim\Http\Request;
use Slim\Http\Response;

final class ForgotSetPassword extends AbstractContainer
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
        $t = $request->getParsedBody()['token'];
        if (!$this->userModule->isUserExistByToken($t)) {
            return $response->write(json_encode(['errors'=>['title'=>'Expire please try again']]));
        }

        $pass = $request->getParsedBody()['forgot_new_password'];
        $pass2 = $request->getParsedBody()['forgot_new_password2'];

        if ($msg = $this->helper->isPasswordInValid($pass, $pass2)) {
            return $response->write(json_encode(['errors'=>['title'=>$msg]]));
        }

        $result = $this->c['userModule']->postNewPassword($pass, $t);

        if ($result) {
            $this->loginModule->setLogined(['id'=>$result['data']['ppmid']]);
        }

        return $response->write(json_encode($this->httpHelper->result));
    }

}
