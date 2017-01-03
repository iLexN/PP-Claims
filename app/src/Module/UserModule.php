<?php

namespace PP\WebPortal\Module;

use PP\WebPortal\AbstractClass\AbstractContainer;
use PP\WebPortal\Module\Model\UserModel;

final class UserModule extends AbstractContainer
{
    /**
     * logined user.
     *
     * @var UserModel
     */
    public $user;

    /**
     * @param string $token
     * @return \Psr\Http\Message\ServerRequestInterface|bool
     */
    public function isUserExistByToken($token)
    {
        $response = $this->httpClient->request('GET', 'forgot-passowrd/'.$token);

        return $this->httpHelper->verifyResponse($response);
    }

    /**
     * @param string $username
     * @return \Psr\Http\Message\ServerRequestInterface|bool
     */
    public function userForgotPassword($username)
    {
        $response = $this->httpClient->request('POST', 'forgot-passowrd', [
                'form_params' => [
                    'user_name' => $username,
                ],
            ]);

        return  $this->httpHelper->verifyResponse($response);
    }

    /**
     * @param array $data
     * @return \Psr\Http\Message\ServerRequestInterface|bool
     */
    public function userForgotUsername($data)
    {
        $response = $this->httpClient->request('POST', 'forgot-username', [
                'form_params' => $data,
            ]);

        return  $this->httpHelper->verifyResponse($response);
    }

    /**
     * @param array $data
     * @return \Psr\Http\Message\ServerRequestInterface|bool
     */
    public function userVerify($data)
    {
        $response = $this->httpClient->request('POST', 'verify', [
                'form_params' => $data,
            ]);

        return  $this->httpHelper->verifyResponse($response);
    }

    /**
     * @param array $data
     * @param int $id
     * @return \Psr\Http\Message\ServerRequestInterface|bool
     */
    public function userSign($data, $id)
    {
        $response = $this->httpClient->request('POST', 'user/'.$id.'/signup', [
                'form_params' => $data,
            ]);

        return  $this->httpHelper->verifyResponse($response);
    }

    /**
     * get User info from session.
     * @param int $id
     * @return UserModel
     */
    public function getUser($id)
    {
        /* @var $item Stash\Interfaces\ItemInterface */
        $item = $this->pool->getItem('User/'.$id.'/info');
        $user = $item->get();

        if ($item->isMiss()) {
            $item->lock();
            $item->expiresAfter($this->c->get('dataCacheConfig')['expiresAfter']);
            $user = new UserModel($this->getUserByAPI($id));
            $this->pool->save($item->set($user));
        }

        return $user;
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
        $response = $this->httpClient->request('GET', 'user/'.$id);
        $result = $this->httpHelper->verifyResponse($response);

        return $result['data'];
    }

    /**
     * @param array $data
     * @return \Psr\Http\Message\ServerRequestInterface|bool
     */
    public function postUserInfoByAPI($data)
    {
        $response = $this->httpClient->request('POST', 'user/'.$this->user['ppmid'], [
                'form_params' => $data,
            ]);

        $this->pool->deleteItem('User/'.$this->user['ppmid'].'/info');

        return  $this->httpHelper->verifyResponse($response);
    }

    /**
     * setnew password from forgotPassword.
     * @param string $pass
     * @param string $token
     * @return \Psr\Http\Message\ServerRequestInterface|bool
     */
    public function postForgotPassword($pass, $token)
    {
        $response = $this->httpClient->request('POST', 'forgot-passowrd/'.$token, [
                'form_params' => [
                    'new_password' => $pass,
                ],
            ]);

        return  $this->httpHelper->verifyResponse($response);
    }
}
