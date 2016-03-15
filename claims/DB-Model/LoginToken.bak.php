<?php
namespace PP\Claims\dbModel;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

class LoginToken extends \Model {
    public static $_table = 'login-token';
    //put your code here

    public function getUser() {
        return $this->belongs_to(__NAMESPACE__ . '\User');
    }

    public function genToken(){
        $uuid1 = Uuid::uuid4();
        try {
            $this->token =$uuid1->toString();
        } catch (UnsatisfiedDependencyException $e) {
            echo 'Caught exception: ' . $e->getMessage() . "\n";
        }
    }
}
