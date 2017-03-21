<?php

namespace PP\WebPortal\Module\UserSubModule;

use PP\WebPortal\AbstractClass\AbstractContainer;

final class AddressModule extends AbstractContainer
{
    public function getUserAddress($id)
    {
        /* @var $item Stash\Interfaces\ItemInterface */
        $item = $this->pool->getItem('User/'.$id.'/address');
        $data = $item->get();

        if ($item->isMiss()) {
            $item->lock();
            $item->expiresAfter($this->c->get('dataCacheConfig')['expiresAfter']);
            $data = $this->getUserAddressByAPI($id);
            $this->pool->save($item->set($data));
        }

        return $data;
    }

    private function getUserAddressByAPI($id)
    {
        $response = $this->httpClient->request('GET', 'user/'.$id.'/address');
        $result = $this->httpHelper->verifyResponse($response);

        return $result['data'];
    }

    public function postUserAddressByAPI($data, $url)
    {
        $response = $this->httpClient->request('POST', $url, [
                'form_params' => $data,
            ]);

        $this->pool->deleteItem('User/'.$data['ppmid'].'/address');
        $this->httpHelper->verifyResponse($response);

        return  $this->httpHelper->result;
    }

    public function delUserAddressByAPI($data, $url)
    {
        $response = $this->httpClient->request('DELETE', $url);

        $this->pool->deleteItem('User/'.$data['ppmid'].'/address');

        return  $this->httpHelper->verifyResponse($response);
    }
}
