<?php

namespace PP\WebPortal\Module;

use PP\WebPortal\AbstractClass\AbstractContainer;
use PP\WebPortal\Module\Model\ClaimModel;

/**
 * Description of PolicyModule.
 *
 * @author user
 */
final class ClaimModule extends AbstractContainer
{
    /**
     *
     * @param array $data
     *
     * @return ClaimModel
     */
    public function newClaim($data)
    {
        return new ClaimModel($data);
    }

    /**
     * getPolices list by user client no.
     *
     * @return ClaimModel
     */
    public function getClaim($id)
    {
        /* @var $item Stash\Interfaces\ItemInterface */
        $item = $this->pool->getItem('Claim/'.$id);
        $data = $item->get();

        if ($item->isMiss()) {
            $item->lock();
            $item->expiresAfter($this->c->get('dataCacheConfig')['expiresAfter']);
            $data = new ClaimModel($this->getOneByAPI($id));
            $this->pool->save($item->set($data));
        }

        return $data;
    }

    public function clearClamID($id)
    {
        $this->pool->deleteItem('Claim/'.$id);
    }

    /**
     * get policies list from API.
     *
     * @return array
     */
    private function getOneByAPI($id)
    {
        $response = $this->httpClient->request('GET', 'claim/' . $id);

        $result = $this->httpHelper->verifyResponse($response);

        return $result['data'];
    }

    public function postClaimByAPI($data, $url)
    {
        $response = $this->httpClient->request('POST', $url, [
                'form_params' => $data,
            ]);

        return  $this->httpHelper->verifyResponse($response);
    }
}
