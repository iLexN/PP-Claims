<?php

namespace PP\WebPortal\Module;

use PP\WebPortal\AbstractClass\AbstractContainer;

final class LoginModule extends AbstractContainer
{
    /**
     * user login.
     *
     * @param array $input
     *
     * @return bool
     */
    public function isUserExist($input)
    {
        $response = $this->c['httpClient']->request('POST', 'login', [
                'form_params' => $input,
            ]);

        return $this->c['httpHelper']->verifyResponse($response);
    }

    /**
     * get User info from session.
     *
     * @return array
     */
    public function getUserByLoginSession()
    {
        $this->userModule->getUser($_SESSION['userLogin']['id']);
    }

    /**
     * check user is login or not.
     *
     * @return bool
     */
    public function isLogined()
    {
        return isset($_SESSION['userLogin']);
    }

    /**
     * set login.
     *
     * @param array $data
     */
    public function setLogined($data)
    {
        $this->logger->info('setlogin', $data);
        $_SESSION['userLogin'] = $data;
    }

    public function setSignUpID($id)
    {
        $_SESSION['tempID'] = $id;
    }
    public function getSignUpID()
    {
        return $_SESSION['tempID'];
    }

    public function setLogout()
    {
        unset($_SESSION['userLogin']);
    }
}
