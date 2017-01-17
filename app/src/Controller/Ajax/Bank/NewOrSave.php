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

        $this->logger->info('user',$this->userModule->user->toArray());
        $this->logger->info('api url ', [$url]);
        $this->logger->info('data ', (array) $request->getParsedBody());

        $result = $this->userModule->postUserBankByAPI($this->data, $url);

        //return $response->withJson((array) $request->getParsedBody());

        return $response->withJson($result);
    }

    private function getApiUrl()
    {
        if ($this->data['banker_transfer_id'] === null) {
            return 'user/'.$this->userModule->user['ppmid'].'/bank-account';
        } else {
            return 'user/'.$this->userModule->user['ppmid'].'/bank-account/'.$this->data['banker_transfer_id'];
        }
    }
}
