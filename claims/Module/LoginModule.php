<?php

namespace PP\Module;

use PP\Claims\dbModel\User;

class LoginModule
{
    /* @var $c \Slim\Container */
    protected $c;
    
    /* @var $user User */
    public $user;

    public function __construct(\Slim\Container $container)
    {
        $this->c = $container;
    }

    /**
     * user email to login,.
     *
     * @param string $email
     *
     * @return bool
     */
    public function login($email)
    {
        $user = User::where('email', $email)->findOne();
        if ($user) {
            $this->user = $user;

            //return $user;
            return true;
        }

        return false;
    }

    /**
     * gen token for login and save into db.
     */
    public function genToken()
    {
        $this->user->genToken();
        $this->user->tokenExpireDatetime = date('Y-m-d H:i:s', strtotime('+1 hours'));
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
                ->where_gt('tokenExpireDatetime', date('Y-m-d H:i:s'))
                ->findOne();

        return $this->user;
    }

    /**
     * save user id in session.
     */
    public function setLogined()
    {
        $_SESSION['userLogin'] = $this->user->id;
    }

    /**
     * get User info from session.
     *
     * @return User
     */
    public function getUserByLgoinSession()
    {
        return User::findOne($_SESSION['userLogin']);
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
}
