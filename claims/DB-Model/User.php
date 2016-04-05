<?php

namespace PP\Claims\dbModel;

use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;
use Ramsey\Uuid\Uuid;

/**
 * @property int $id
 * @property string $token
 * @property string $tokenExpireDatetime
 */
class User extends \Model
{
    public static $_table = 'user';

    /**
     * gen new Token for login.
     */
    public function genToken()
    {
        $uuid = Uuid::uuid4();
        try {
            $this->token = $uuid->toString();
        } catch (UnsatisfiedDependencyException $e) {
            echo 'Caught exception: '.$e->getMessage()."\n";
        }
    }
}
