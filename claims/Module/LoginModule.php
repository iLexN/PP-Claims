<?php

namespace PP\Module;

use PP\Claims\dbModel\User;

class LoginModule
{
    /* @var $user User */
    private $user;

    public function __construct()
    {
    }

    /**
     * user email to login,.
     *
     * @param string $email
     *
     * @return bool|User
     */
    public function login($email)
    {
        $user = User::where('email', $email)->findOne();
        if ($user) {
            $this->user = $user;

            return $user;
        } else {
            return false;
        }
    }

    /**
     * gen token for login and save into db.
     */
    public function genToken()
    {
        $this->user->genToken();
        $this->user->tokenDatetime = date('Y-m-d H:i:s');
        $this->user->save();
    }

    /**
     * check token from the email , token expire 1 hr.
     *
     * @param string $token
     *
     * @return User
     */
    public function checkToken($token)
    {
        $this->user = User::where('token', $token)
                ->where_gt('tokenDatetime', date('Y-m-d H:i:s', strtotime('-1 hours')))
                ->findOne();

        return $this->user;
    }

    /**
     * save user id in session.
     */
    public function setLogin()
    {
        $_SESSION['userLogin'] = $this->user->id;
    }

    /**
     * get User info from session.
     *
     * @return User
     */
    public function getUser()
    {
        return User::findOne($_SESSION['userLogin']);
    }
}
