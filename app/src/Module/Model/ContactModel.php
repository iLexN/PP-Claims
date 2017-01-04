<?php

namespace PP\WebPortal\Module\Model;

use PP\WebPortal\Module\Model\AbstractClass\ModelAbstract;

class ContactModel extends ModelAbstract
{
    public function __construct($data)
    {
        $this->data = $data;
    }

    public function fullAddess()
    {
        return $this->data['address_1'].' '.$this->data['address_2'].' '.$this->data['address_3'].' '.$this->data['address_4'].' '.$this->data['address_5'];
    }

    public function gmailLink()
    {
        return sprintf('https://www.google.com.hk/maps/place/%s/@%s,%s,18z', $this->data['gmap_keyword'], $this->data['gmap_lat'], $this->data['gmap_lng']);
    }

    public function tel($k)
    {
        return $this->numOnly($this->data[$k]);
    }

    private function numOnly($v)
    {
        return  preg_replace('/\\((\w+)\\)/i', '$2', $v);
    }
}
