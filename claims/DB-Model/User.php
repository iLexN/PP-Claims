<?php

namespace PP\Claims\dbModel;

/**
 * @property int $id
 * @property string $token
 * @property string $tokenExpireDatetime
 */
class User extends \Model
{
    public static $_table = 'user';

}
