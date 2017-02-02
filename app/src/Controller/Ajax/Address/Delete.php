<?php

namespace PP\WebPortal\Controller\Ajax\Address;

use PP\WebPortal\AbstractClass\AbstractContainer;
use Slim\Http\Request;
use Slim\Http\Response;

final class Delete extends AbstractContainer
{
    /**
     * @var array
     */
    private $data;

    public function __invoke(Request $request, Response $response, array $args)
    {
        $this->data = (array) $request->getParsedBody();

        if (!$args['id'] == $this->data['id'] || !$this->checkbelogTo()) {
            throw new \Slim\Exception\NotFoundException($request, $response);
        }

        $result = $this->userModule->delUserAddressByAPI($this->data, $this->getApiUrl());

        return $response->withJson($result);
    }

    private function checkbelogTo()
    {
        if ($this->userModule->user['ppmid'] == $this->data['ppmid']) {
            return true;
        }

        $people = $this->userModule->getPeopleList($this->userModule->user['ppmid']);
        if ($people[$this->data['ppmid']]) {
            return true;
        }

        return false;
    }

    private function getApiUrl()
    {
        return 'user/'.$this->data['ppmid'].'/address/' . $this->data['id'];
    }
}
