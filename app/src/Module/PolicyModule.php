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
        $item = $this->c['pool']->getItem('User/'.$user['ppmid'].'/Policies');
        $policies = $item->get();

        if ($item->isMiss()) {
            $item->lock();
            $item->expiresAfter($this->c->get('dataCacheConfig')['expiresAfter']);
            $policies = $this->getPoliciesByAPI($user['ppmid']);
            $this->c['pool']->save($item->set($policies));
        }

        return $policies;
    }

    /**
     * get policies from API.
     *
     * @param int $id
     *
     * @return array
     */
    private function getPoliciesByAPI($id)
    {
        $response = $this->c['httpClient']->request('GET', 'user/'.$id.'/policy');

        $result = $this->c['httpHelper']->verifyResponse($response);

        return $result['data'];
    }
}
