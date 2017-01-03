<?php

namespace PP\WebPortal\Module\Model;

use PP\WebPortal\Module\Model\AbstractClass\ModelAbstract;

class PolicyModel extends ModelAbstract
{
    public function __construct($data)
    {
        $this->data = $data;
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
