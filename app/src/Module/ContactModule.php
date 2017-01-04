<?php

namespace PP\WebPortal\Module;

use PP\WebPortal\AbstractClass\AbstractContainer;
use PP\WebPortal\Module\Model\ContactModel;

/**
 * Description of PolicyModule.
 *
 * @author user
 */
final class ContactModule extends AbstractContainer
{
    /**
     * getPolices list by user client no.
     *
     * @return array
     */
    public function getContact()
    {

        /* @var $item Stash\Interfaces\ItemInterface */
        $item = $this->pool->getItem('OfficeContact');
        $data = $item->get();

        if ($item->isMiss()) {
            $item->lock();
            $item->expiresAfter($this->c->get('dataCacheConfig')['expiresAfter']);
            $data = $this->factory( $this->getByAPI());
            $this->pool->save($item->set($data));
        }
        return $data;
    }

    /**
     * get policies list from API.
     *
     * @return array
     */
    private function getByAPI()
    {
        $response = $this->httpClient->request('GET', 'office-info');

        $result = $this->httpHelper->verifyResponse($response);

        return $result['data'];
    }

    private function factory($list)
    {
        $newList = [];
        foreach ($list as $data) {
            $newList[] = new ContactModel($data);
        }

        return $newList;
    }
}
