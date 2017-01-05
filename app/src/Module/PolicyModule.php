<?php

namespace PP\WebPortal\Module;

use PP\WebPortal\AbstractClass\AbstractContainer;

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
            //$policies = $this->policyFactory($this->getPoliciesByAPI($user['ppmid']));
            $policies = $this->getPoliciesByAPI($user['ppmid']);
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
}
