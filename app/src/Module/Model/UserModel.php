<?php

namespace PP\WebPortal\Module\Model;

use PP\WebPortal\Module\Model\AbstractClass\ModelAbstract;

class UserModel extends ModelAbstract
{
    public function __construct($data)
    {
        $this->data = $data;
    }

    public function fullName()
    {
        return $this->data['first_name'].' '.$this->data['middle_name'].' '.$this->data['last_name'];
    }
}
