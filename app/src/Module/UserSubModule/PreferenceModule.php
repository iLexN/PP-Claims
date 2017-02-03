<?php

namespace PP\WebPortal\Module\UserSubModule;

use PP\WebPortal\AbstractClass\AbstractContainer;

final class PreferenceModule extends AbstractContainer
{
    public function getUserPreference($id)
    {
        /* @var $item Stash\Interfaces\ItemInterface */
        $item = $this->pool->getItem('User/'.$id.'/preference');
        $data = $item->get();

        if ($item->isMiss()) {
            $item->lock();
            $item->expiresAfter($this->c->get('dataCacheConfig')['expiresAfter']);
            $data = $this->getUserPreferenceByAPI($id);
            $this->pool->save($item->set($data));
        }

        return $data;
    }

    public function checkUserPreference($inArray, $id)
    {
        if (empty($inArray['currency']) || empty($inArray['currency_receive'])) {
            return;
        }
        $serverArray = $this->getUserPreference($id);

        if ($this->checkUserPreferenceUpdate($inArray, $serverArray)) {
            $this->updateUserPreference($inArray, $id);
        }
    }

    private function checkUserPreferenceUpdate($inArray, $serverArray)
    {
        return $inArray['currency'] !== $serverArray['currency'] || $inArray['currency_receive'] !== $serverArray['currency_receive'];
    }

    private function updateUserPreference($inArray, $id)
    {
        $response = $this->httpClient->request('POST', 'user/'.$id.'/preference', [
                'form_params' => [
                    'currency'         => $inArray['currency'],
                    'currency_receive' => $inArray['currency_receive'],
                ],
            ]);
        $this->httpHelper->verifyResponse($response);
        $this->pool->deleteItem('User/'.$id.'/preference');
    }

    private function getUserPreferenceByAPI($id)
    {
        $response = $this->httpClient->request('GET', 'user/'.$id.'/preference');

        $result = $this->httpHelper->verifyResponse($response);

        return $result['data'];
    }
}
