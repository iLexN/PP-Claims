<?php

namespace PP\WebPortal\Controller\Ajax\System;

use PP\WebPortal\AbstractClass\AbstractContainer;
use Slim\Http\Request;
use Slim\Http\Response;

final class UserVerify extends AbstractContainer
{
    public function __invoke(Request $request, Response $response, array $args)
    {
        $data = $request->getParsedBody();

        $result = $this->userModule->userVerify($this->processData($data));

        if ($result) {
            $this->loginModule->setSignUpID($data['ppmid']);
        }

        return $response->write(json_encode($this->httpHelper->result));
    }

    private function processData($data)
    {
        $out = [];
        $out['ppmid'] = $data['ppmid'];
        $out['date_of_birth'] = $data['year']  . '-' . $data['month'] . '-' . $data['day'];

        return $out;
    }
}
