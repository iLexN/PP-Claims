<?php

namespace PP\WebPortal\Module\UserSubModule;

use PP\WebPortal\AbstractClass\AbstractContainer;
use PP\WebPortal\Module\Model\BankModel;
use PP\WebPortal\Module\Model\ListModel;

final class BankModule extends AbstractContainer
{
    public function getUserBank($id)
    {
        /* @var $item Stash\Interfaces\ItemInterface */
        $item = $this->pool->getItem('User/'.$id.'/bank');
        $data = $item->get();

        if ($item->isMiss()) {
            $item->lock();
            $item->expiresAfter($this->c->get('dataCacheConfig')['expiresAfter']);
            $data = $this->factoryBank($this->getUserBankByAPI($id));
            $this->pool->save($item->set($data));
        }

        return $data;
    }

    private function getUserBankByAPI($id)
    {
        $response = $this->httpClient->request('GET', 'user/'.$id.'/bank-account');
        $result = $this->httpHelper->verifyResponse($response);

        return $result['data'];
    }

    public function postUserBankByAPI($data, $url)
    {
        $response = $this->httpClient->request('POST', $url, [
                'form_params' => $data,
            ]);

        $this->pool->deleteItem('User/'.$this->userModule->user['ppmid'].'/bank');
        $this->httpHelper->verifyResponse($response);

        return  $this->httpHelper->result;
    }

    public function delUserBankByAPI($id)
    {
        $url = 'user/'.$this->userModule->user['ppmid'].'/bank-account/'.$id;
        $response = $this->httpClient->request('DELETE', $url);

        $this->pool->deleteItem('User/'.$this->userModule->user['ppmid'].'/bank');

        return  $this->httpHelper->verifyResponse($response);
    }

    private function factoryBank($list)
    {
        $newList = new ListModel();

        if ($list === null) {
            return $newList;
        }

        foreach ($list as $data) {
            $newList->push(new BankModel($data, $this->currencyText));
        }

        return $newList;
    }

    public function checkBankInfo($banks)
    {
        if (empty($banks->data)) {
            $preference = $this->preferenceModule->getUserPreference($this->userModule->user['ppmid']);
            $banks->push(new BankModel([
                'currency' => $preference['currency'],
            ], $this->currencyText));
        }

        return $banks;
    }
}
