<?php

namespace PP\WebPortal\Module;

use PP\WebPortal\AbstractClass\AbstractContainer;

final class UserModule extends AbstractContainer
{
    /**
     * @var array
     */
    public $user;

    public function isUserExistByToken($token)
    {
        $response = $this->c['httpClient']->request('GET', 'forgot-passowrd/'.$token);

        return $this->c['httpHelper']->verifyResponse($response);
    }

    /**
     * get User info from session.
     *
     * @return array
     */
    public function getUser($id)
    {

        /* @var $item Stash\Interfaces\ItemInterface */
        $item = $this->c['pool']->getItem('User/'.$id.'/info');
        $this->user = $item->get();

        if ($item->isMiss()) {
            $item->lock();
            $item->expiresAfter($this->c->get('dataCacheConfig')['expiresAfter']);
            $this->user = $this->getUserByAPI($id);
            $this->c['pool']->save($item->set($this->user));
        }
        $this->c['logger']->info('userinfo', $this->user);
        //return $this->user;
    }

    /**
     * getUserInfo From API.
     *
     * @param int $id
     *
     * @return array
     */
    private function getUserByAPI($id)
    {
        $response = $this->c['httpClient']->request('GET', 'user/'.$id);

        $result = $this->c['httpHelper']->verifyResponse($response);

        return $result['data'];
    }

    public function postUserInfoByAPI($data)
    {
        $response = $this->c['httpClient']->request('POST', 'user/'.$this->user['ppmid'], [
                'form_params' => $data,
            ]);

        $this->c['pool']->clear('User/'.$this->user['ppmid'].'/info');

        return  $this->c['httpHelper']->verifyResponse($response);
    }

    public function postNewPassword($pass, $token)
    {
        $response = $this->c['httpClient']->request('POST', 'forgot-passowrd/'.$token, [
                'form_params' => [
                    'new_password' => $pass,
                ],
            ]);

        return  $this->c['httpHelper']->verifyResponse($response);
    }
}
