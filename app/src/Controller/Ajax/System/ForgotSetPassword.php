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
        $data = $request->getParsedBody();

        if (!$this->userModule->isUserExistByToken($data['token'])) {
            //return $response->write(json_encode(['errors' => ['title' => $this->langText['forgotSetPasswordExpire']]]));
            return $response->withJson(['errors' => ['title' => $this->langText['forgotSetPasswordExpire']]]);
        }

        if ($msg = $this->helper->isPasswordInValid($data['forgot_new_password'], $data['forgot_new_password2'])) {
            //return $response->write(json_encode(['errors' => ['title' => $msg]]));
            return $response->withJson(['errors' => ['title' => $msg]]);
        }

        $result = $this->c['userModule']->postNewPassword($data['forgot_new_password'], $data['token']);

        if ($result) {
            $this->loginModule->setLogined(['id' => $result['data']['ppmid']]);
        }

        //return $response->write(json_encode($this->httpHelper->result));
        return $response->withJson($this->httpHelper->result);
    }
}
