<?php

namespace PP\WebPortal\Controller\Ajax\Bank;

use PP\WebPortal\AbstractClass\AbstractContainer;
use Slim\Http\Request;
use Slim\Http\Response;

final class NewOrSave extends AbstractContainer
{
    /**
     *
     * @var array
     */
    private $data;

    public function __invoke(Request $request, Response $response, array $args)
    {
        $this->data = (array) $request->getParsedBody();
        $url = $this->getApiUrl();

        $result = $this->userModule->postUserBankByAPI($this->data, $url);

        return $response->withJson($result);
    }

    private function getApiUrl()
    {
        if ($this->data['banker_transfer_id'] === null || $this->data['banker_transfer_id'] === '') {
            return 'user/'.$this->userModule->user['ppmid'].'/bank-account';
        } else {
            return 'user/'.$this->userModule->user['ppmid'].'/bank-account/'.$this->data['banker_transfer_id'];
        }
    }
}
