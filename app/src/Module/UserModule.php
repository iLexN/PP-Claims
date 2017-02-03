<?php

namespace PP\WebPortal\Module;

use PP\WebPortal\AbstractClass\AbstractContainer;
use PP\WebPortal\Module\Model\BankModel;
use PP\WebPortal\Module\Model\ListModel;
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
     *
     * @return \Psr\Http\Message\ServerRequestInterface|bool
     */
    public function isUserExistByToken($token)
    {
        $response = $this->httpClient->request('GET', 'forgot-passowrd/'.$token);

        return $this->httpHelper->verifyResponse($response);
    }

    /**
     * @param string $username
     *
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
     *
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
     *
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
     * @param int   $id
     *
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
     *
     * @param int $id
     *
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

    public function getUserReNewInfo($id)
    {
        /* @var $item Stash\Interfaces\ItemInterface */
        $item = $this->pool->getItem('User/'.$id.'/renew');
        $user = $item->get();

        if ($item->isMiss()) {
            $item->lock();
            $item->expiresAfter($this->c->get('dataCacheConfig')['expiresAfter']);
            $user = $this->getUserReNewByAPI($id);
            $this->pool->save($item->set($user));
        }

        return $user;
    }

    /**
     * get User info from session.
     *
     * @param int $id
     *
     * @return ListModel
     */
    public function getPeopleList($id)
    {
        /* @var $item Stash\Interfaces\ItemInterface */
        $item = $this->pool->getItem('User/'.$id.'/people');
        $user = $item->get();

        if ($item->isMiss()) {
            $item->lock();
            $item->expiresAfter($this->c->get('dataCacheConfig')['expiresAfter']);
            $user = $this->factoryUser($this->getPeopleListByAPI($id));
            $this->pool->save($item->set($user));
        }

        return $user;
    }

    public function getUserBank($id)
    {
        /* @var $item Stash\Interfaces\ItemInterface */
        $item = $this->pool->getItem('User/'.$id.'/bank');
        $data = $item->get();

        if ($item->isMiss()) {
            $item->lock();
            $item->expiresAfter($this->c->get('dataCacheConfig')['expiresAfter']);
            $data = $this->factoryBank($this->getUserBankByAPI($id));
            $this->pool->save($item->set($data));
        }

        return $data;
    }

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

    public function checkUserPreference($inArray)
    {
        if (empty($inArray['currency']) || empty($inArray['currency_receive'])) {
            return;
        }
        $serverArray = $this->getUserPreference($this->user['ppmid']);

        if ($this->checkUserPreferenceUpdate($inArray, $serverArray)) {
            $this->updateUserPreference($inArray);
        }
    }

    private function checkUserPreferenceUpdate($inArray, $serverArray)
    {
        return $inArray['currency'] !== $serverArray['currency'] || $inArray['currency_receive'] !== $serverArray['currency_receive'];
    }

    private function updateUserPreference($inArray)
    {
        $response = $this->httpClient->request('POST', 'user/'.$this->user['ppmid'].'/preference', [
                'form_params' => [
                    'currency'         => $inArray['currency'],
                    'currency_receive' => $inArray['currency_receive'],
                ],
            ]);
        $this->httpHelper->verifyResponse($response);
        $this->pool->deleteItem('User/'.$this->user['ppmid'].'/preference');
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

    private function getUserReNewByAPI($id)
    {
        $response = $this->httpClient->request('GET', 'user/'.$id.'/renew');
        $result = $this->httpHelper->verifyResponse($response);

        return $result['data'];
    }

    private function getPeopleListByAPI($id)
    {
        $response = $this->httpClient->request('GET', 'user/'.$id.'/people');
        $result = $this->httpHelper->verifyResponse($response);

        return $result['data'];
    }

    private function getUserBankByAPI($id)
    {
        $response = $this->httpClient->request('GET', 'user/'.$id.'/bank-account');
        $result = $this->httpHelper->verifyResponse($response);

        return $result['data'];
    }

    private function getUserPreferenceByAPI($id)
    {
        $response = $this->httpClient->request('GET', 'user/'.$id.'/preference');

        $result = $this->httpHelper->verifyResponse($response);

        return $result['data'];
    }

    /**
     * set new password from forgotPassword.
     *
     * @param string $pass
     * @param string $token
     *
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

    public function postUserInfo($data, $id)
    {
        $response = $this->httpClient->request('POST', 'user/'.$id, [
                'form_params' => $data,
            ]);

        $this->pool->deleteItem('User/'.$id.'/renew');

        return  $this->httpHelper->verifyResponse($response);
    }

    public function postUserBankByAPI($data, $url)
    {
        $response = $this->httpClient->request('POST', $url, [
                'form_params' => $data,
            ]);

        $this->pool->deleteItem('User/'.$this->user['ppmid'].'/bank');

        return  $this->httpHelper->verifyResponse($response);
    }

    public function delUserBankByAPI($id)
    {
        $url = 'user/'.$this->user['ppmid'].'/bank-account/'.$id;
        $response = $this->httpClient->request('DELETE', $url);

        $this->pool->deleteItem('User/'.$this->user['ppmid'].'/bank');

        return  $this->httpHelper->verifyResponse($response);
    }

    private function factoryBank($list)
    {
        $newList = new ListModel();

        if ($list === null) {
            return $newList;
        }

        foreach ($list as $data) {
            $newList->push(new BankModel($data, $this->currencyText));
        }

        return $newList;
    }

    private function factoryUser($list)
    {
        $newList = new ListModel();
        if ($list === null) {
            return $newList;
        }
        foreach ($list as $data) {
            $newList->push(new UserModel($data), true);
        }

        return $newList;
    }
}
