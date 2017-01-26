<?php

namespace PP\WebPortal\Module\Model;

use PP\WebPortal\Module\Model\AbstractClass\ModelAbstract;

class UserModel extends ModelAbstract
{
    public function __construct($data)
    {
        $this->data = $data;
        $this->setFullName();
    }

    public function fullName()
    {
        return $this->data['first_name'].' '.$this->data['middle_name'].' '.$this->data['last_name'];
    }

    private function setFullName()
    {
        $this->data['fullName'] = $this->fullName();
    }

    public function isHolder()
    {
        return $this->data['relationship'] === 'PolicyHolder';
    }

    public function getKey()
    {
        return $this->data['ppmid'];
    }

    public function setAddress($ar)
    {
        if ($ar === null) {
            $this->data['address'] = [];
        } else {
            $this->data['address'] = $ar;
        }
    }

    public function setReNew($ar)
    {
        $this->data['renew'] = $ar;
    }
}
