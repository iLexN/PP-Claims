<?php

namespace PP\WebPortal\Controller\Ajax\Member;

use PP\WebPortal\AbstractClass\AbstractContainer;
use Slim\Http\Request;
use Slim\Http\Response;

final class Password extends AbstractContainer
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

        if ($msg = $this->helper->isPasswordInValid($data['new_password'], $data['confirm_password'])) {
            return $response->withJson(['errors' => ['title' => $msg]]);
        }

        $this->userModule->postUpdatePassword($data);

        return $response->withJson($this->httpHelper->result);
    }
}
