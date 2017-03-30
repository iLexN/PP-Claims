<?php

namespace PP\WebPortal\Controller\Ajax\Member;

use PP\WebPortal\AbstractClass\AbstractContainer;
use PP\WebPortal\Module\Model\UserModel;
use Slim\Http\Request;
use Slim\Http\Response;

final class InfoUpdate extends AbstractContainer
{
    public function __invoke(Request $request, Response $response, array $args)
    {
        $people = $this->userModule->getPeopleList($this->userModule->user['ppmid']);

        if (!$people[$args['id']]) {
            throw new \Slim\Exception\NotFoundException($request, $response);
        }

        $result = $this->userModule->postUserInfo($this->getPostData($people[$args['id']], (array) $request->getParsedBody()), $args['id']);

        return $response->withJson($result);
    }

    private function getPostData(UserModel $people, $post)
    {
        return array_merge($people->getFullNameArray(), $post);
    }
}
