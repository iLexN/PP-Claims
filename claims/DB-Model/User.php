<?php

namespace PP\Claims\dbModel;

use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;
use Ramsey\Uuid\Uuid;

/**
 * @property string $token
 */
class User extends \Model
{
    public static $_table = 'user';

    /**
     * gen new Token for login
     */
    public function genToken()
    {
        $uuid1 = Uuid::uuid4();
        try {
            $this->token = $uuid1->toString();
        } catch (UnsatisfiedDependencyException $e) {
            echo 'Caught exception: '.$e->getMessage()."\n";
        }
    }
}
