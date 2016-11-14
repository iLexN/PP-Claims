<?php

namespace PP\WebPortal\Module;

use PP\WebPortal\AbstractClass\AbstractContainer;
use PP\WebPortal\Module\Model\UserModel;

final class UserModule extends AbstractContainer
{
    /**
     * @var UserModel
     */
    public $user;

    /**
     * @return \Psr\Http\Message\ServerRequestInterface
     */
    public function isUserExistByToken($token)
    {
        $response = $this->c['httpClient']->request('GET', 'forgot-passowrd/'.$token);

        return $this->c['httpHelper']->verifyResponse($response);
    }

    /**
     * @return \Psr\Http\Message\ServerRequestInterface
     */
    public function userForgotPassword($username)
    {
        $response = $this->c['httpClient']->request('POST', 'forgot-passowrd', [
                'form_params' => [
                    'user_name' => $username,
                ],
            ]);

        return  $this->c['httpHelper']->verifyResponse($response);
    }

    /**
     * @return \Psr\Http\Message\ServerRequestInterface
     */
    public function userForgotUsername($data)
    {
        $response = $this->c['httpClient']->request('POST', 'forgot-username', [
                'form_params' => $data,
            ]);

        return  $this->c['httpHelper']->verifyResponse($response);
    }

    /**
     * @return \Psr\Http\Message\ServerRequestInterface
     */
    public function userVerify($data)
    {
        $response = $this->c['httpClient']->request('POST', 'verify', [
                'form_params' => $data,
            ]);

        return  $this->c['httpHelper']->verifyResponse($response);
    }

    /**
     * @return \Psr\Http\Message\ServerRequestInterface
     */
    public function userSign($data, $id)
    {
        $response = $this->c['httpClient']->request('POST', 'user/'.$id.'/signup', [
                'form_params' => $data,
            ]);

        return  $this->c['httpHelper']->verifyResponse($response);
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
            $this->user = new UserModel($this->getUserByAPI($id));
            $this->c['pool']->save($item->set($this->user));
        }
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
        $result = $this->httpHelper->verifyResponse($response);

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
