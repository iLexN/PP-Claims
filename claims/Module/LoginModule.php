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

    public function genToken()
    {
        $this->user->genToken();
        $this->user->tokenDatetime = date('Y-m-d H:i:s');
        $this->user->save();

        //return $this->user->token;
    }

    public function checkToken($token)
    {
        $this->user = User::where('token', $token)
                ->where_gt('tokenDatetime', date('Y-m-d H:i:s', strtotime('-1 hours')))
                ->findOne();

        return $this->user;
    }

    public function setLogin()
    {
        $_SESSION['userLogin'] = $this->user->id;
    }

    public function getUser()
    {
        return User::findOne($_SESSION['userLogin']);
    }
}
