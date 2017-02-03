<?php

namespace PP\WebPortal\Module\UserSubModule;

use PP\WebPortal\AbstractClass\AbstractContainer;

final class HolderModule extends AbstractContainer
{
    public function getHolderInfo($id)
    {
        /* @var $item Stash\Interfaces\ItemInterface */
        $item = $this->pool->getItem('Hodler/'.$id);
        $data = $item->get();

        if ($item->isMiss()) {
            $item->lock();
            $item->expiresAfter($this->c->get('dataCacheConfig')['expiresAfter']);
            $data = $this->getHolderByAPI($id);
            $this->pool->save($item->set($data));
        }

        return $data;
    }

    private function getHolderByAPI($id)
    {
        $response = $this->httpClient->request('GET', 'holder/'.$id);
        $result = $this->httpHelper->verifyResponse($response);

        return $result['data'];
    }

    public function postHolderInfo($data, $id)
    {
        $response = $this->httpClient->request('POST', 'holder/'.$id, [
                'form_params' => $data,
            ]);

        $this->pool->deleteItem('Hodler/'.$id);

        return  $this->httpHelper->verifyResponse($response);
    }
}
