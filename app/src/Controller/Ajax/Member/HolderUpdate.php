<?php

namespace PP\WebPortal\Controller\Ajax\Member;

use PP\WebPortal\AbstractClass\AbstractContainer;
use Slim\Http\Request;
use Slim\Http\Response;

final class HolderUpdate extends AbstractContainer
{
    public function __invoke(Request $request, Response $response, array $args)
    {
        $user = $this->userModule->user;

        if ($user['holder_id'] != $args['id']) {
            throw new \Slim\Exception\NotFoundException($request, $response);
        }

        $result = $this->holderModule->postHolderInfo((array) $request->getParsedBody(), $args['id']);

        return $response->withJson($result);
    }
}
