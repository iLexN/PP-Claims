<?php

namespace PP\WebPortal\Module;

use PP\WebPortal\AbstractClass\AbstractContainer;
use PP\WebPortal\Module\Model\ListModel;
use PP\WebPortal\Module\Model\PolicyModel;

/**
 * Description of PolicyModule.
 *
 * @author user
 */
final class PolicyModule extends AbstractContainer
{
    /**
     * getPolices list by user client no.
     *
     * @return array
     */
    public function getPolices()
    {
        $user = $this->userModule->user;

        /* @var $item Stash\Interfaces\ItemInterface */
        $item = $this->pool->getItem('User/'.$user['ppmid'].'/Policies');
        $policies = $item->get();

        if ($item->isMiss()) {
            $item->lock();
            $item->expiresAfter($this->c->get('dataCacheConfig')['expiresAfter']);
            $policies = $this->factory($this->getPoliciesByAPI($user['ppmid']));
            $this->pool->save($item->set($policies));
        }

        return $policies;
    }

    /**
     * get policies list from API.
     *
     * @param int $id
     *
     * @return array
     */
    private function getPoliciesByAPI($id)
    {
        $response = $this->httpClient->request('GET', 'user/'.$id.'/policy');

        $result = $this->httpHelper->verifyResponse($response);

        return $result['data'];
    }

    public function getClaimList($id){

        /* @var $item Stash\Interfaces\ItemInterface */
        $item = $this->pool->getItem('UserPolicy/'.$id.'/claimlist');
        $policies = $item->get();

        if ($item->isMiss()) {
            $item->lock();
            $item->expiresAfter($this->c->get('dataCacheConfig')['expiresAfter']);
            $policies = $this->getClaimListByAPI($id);
            $this->pool->save($item->set($policies));
        }

        return $policies;
    }

    /**
     * get policies list from API.
     *
     * @param int $id
     *
     * @return array
     */
    private function getClaimListByAPI($id)
    {
        $response = $this->httpClient->request('GET', 'user-policy/'.$id.'/claim');

        $result = $this->httpHelper->verifyResponse($response);

        return $result['data'];
    }
    public function clearClamList($id)
    {
        $this->pool->deleteItem('UserPolicy/'.$id.'/claimlist');
    }


    private function factory($list)
    {
        $newList = new ListModel();
        foreach ($list as $data) {
            $newList->push(new PolicyModel($data, $this->currencyText), true);
        }

        return $newList;
    }
}
