<?php

namespace PP\WebPortal\Module\Model;

use PP\WebPortal\Module\Model\AbstractClass\ModelAbstract;

class PolicyModel extends ModelAbstract
{
    private $currency;

    public function __construct($data, $currency)
    {
        $this->data = $data;
        $this->currency = $currency;
        $this->init();
    }

    private function init()
    {
        $this->data['medical_currency_display'] = $this->data['medical_currency'].' '.$this->currency[$this->data['medical_currency']];
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        return $this->data['status'] === 'Active' ? true : false;
    }

    /**
     * @return string
     */
    public function getPremiumPaid()
    {
        return $this->data['pivot']['premium_paid'];
    }

    /**
     * @return array
     */
    public function getAdvisor()
    {
        return $this->data['advisor'];
    }
}
