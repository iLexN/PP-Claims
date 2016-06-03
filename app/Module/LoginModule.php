<?php

namespace PP\Module;

use PP\Claims\dbModel\User;

class LoginModule
{
    /**
     * @var \Slim\Container
     */
    protected $c;

    /**
     * @var User
     */
    public $user;

    public function __construct(\Slim\Container $container)
    {
        $this->c = $container;
    }

    /**
     * user email to login.
     *
     * @param array $input
     *
     * @return bool
     */
    public function isUserExist($input)
    {
        
        $response = $this->c['httpClient']->request('POST', 'login', [
                'form_params' => $input
            ]);

        return $this->c['httpHelper']->verifyResponse($response);
    }

    /**
     * gen token for login and save into db.
     */
    public function genToken()
    {
        $this->user->genToken();
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
     * set login
     *
     * @param type $data
     */
    public function setLogined($data)
    {
        $this->c->logger->info('user' , $data);
        //$_SESSION['userLogin'] = $this->user->id;
        $_SESSION['userLogin'] = $data;
    }

    /**
     * get User info from session.
     *
     * @return User
     */
    public function getUserByLoginSession()
    {
        //var_dump($_SESSION['userLogin']);
        $this->c->logger->info('getUserByLoginSession' , $_SESSION['userLogin']);

        /* @var $item Stash\Interfaces\ItemInterface */
        $item = $this->c['pool']->getItem('User/'.$_SESSION['userLogin']['id'].'/info');
        $this->user = $item->get();

        if ($item->isMiss()) {
            $item->lock();
            $item->expiresAfter($this->c->get('dataCacheConfig')['expiresAfter']);
            //$this->user = User::findOne($_SESSION['userLogin']);
            $this->user = $this->getUserByAPI($_SESSION['userLogin']['id']);
            $this->c['pool']->save($item->set($this->user));
        }

        return $this->user;
    }

    public function getUserByAPI($id){

        $this->c->logger->info('cache user');

        $response = $this->c['httpClient']->request('GET', 'user/'.$id);

        $result =json_decode((string) $response->getBody(), 1);
        if (isset($result['error']) || $response->getStatusCode() != 200) {
            $log = [
                'getStatusCode' => $response->getStatusCode(),
                'body'          => (string) $response->getBody(),
            ];
            $this->c->logger->error('post file response', $log);
            return false;
        }

        return $result['data'];
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

    public function setLogout(){
        unset($_SESSION['userLogin']);
    }
}
