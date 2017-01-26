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

        if ( $user['holder_id'] != $args['id'] ) {
            return $response->withJson([]);
        }

        $result = $this->userModule->postHolderInfo((array) $request->getParsedBody(),$args['id']);

        return $response->withJson($result);

    }
}
