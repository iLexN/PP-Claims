<?php

namespace PP\WebPortal\Controller\Ajax\Bank;

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

        // todo : check bank is user

        if ($this->data['banker_transfer_id'] === $args['id']) {
            $result = $this->userModule->delUserBankByAPI($args['id']);

            return $response->withJson($result);
        }

        throw new \Slim\Exception\NotFoundException($request, $response);
    }
}
