<?php

namespace PP\WebPortal\Module\Model;

class UserModel implements \ArrayAccess
{
    //put your code here

    /**
     * user info.
     *
     * @var array
     */
    private $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function fullName()
    {
        return $this->user['first_name'].' '.$this->user['middle_name'].' '.$this->user['last_name'];
    }

    public function offsetSet($offset, $value)
    {
        $this->user[$offset] = $value;
    }

    public function offsetExists($offset)
    {
        return isset($this->user[$offset]);
    }

    public function offsetUnset($offset)
    {
        unset($this->user[$offset]);
    }

    public function offsetGet($offset)
    {
        return isset($this->user[$offset]) ? $this->user[$offset] : null;
    }
}
