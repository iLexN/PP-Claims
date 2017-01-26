<?php

namespace PP\WebPortal\Controller\Ajax\Member;

use PP\WebPortal\AbstractClass\AbstractContainer;
use Slim\Http\Request;
use Slim\Http\Response;

final class InfoUpdate extends AbstractContainer
{
    public function __invoke(Request $request, Response $response, array $args)
    {
        $people = $this->userModule->getPeopleList($this->userModule->user['ppmid']);

        if ( !$people[$args['id']] ) {
            return $response->withJson([]);
        }

        $result = $this->userModule->postUserInfo((array) $request->getParsedBody(),$args['id']);

        return $response->withJson($result);
    }
}
