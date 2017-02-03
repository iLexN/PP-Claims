<?php

namespace PP\WebPortal\Controller\Ajax\Member;

use PP\WebPortal\AbstractClass\AbstractContainer;
use Slim\Http\Request;
use Slim\Http\Response;

final class GetUserDetails extends AbstractContainer
{
    public function __invoke(Request $request, Response $response, array $args)
    {
        $a = [];
        $a['address'] = $this->addressModule->getUserAddress($args['id']);
        $a['renew'] = $this->userModule->getUserReNewInfo($args['id']);

        return $response->withJson($a);
    }
}
