<?php

namespace PP\WebPortal\Module\Model;

class PolicyModel implements \ArrayAccess
{
    /**
     * policy info.
     *
     * @var array
     */
    private $policy;

    public function __construct($policy)
    {
        $this->policy = $policy;
    }

    /**
     *
     * @return bool
     */
    public function isActive()
    {
        return $this->policy['status'] === 'Active' ? true : false;
    }

    /**
     *
     * @return string
     */
    public function getPremiumPaid()
    {
        return $this->policy['pivot']['premium_paid'];
    }

    /**
     *
     * @return array
     */
    public function getAdvisor()
    {
        return $this->policy['advisor'];
    }


    public function offsetSet($offset, $value)
    {
        $this->policy[$offset] = $value;
    }

    public function offsetExists($offset)
    {
        return isset($this->policy[$offset]);
    }

    public function offsetUnset($offset)
    {
        unset($this->policy[$offset]);
    }

    public function offsetGet($offset)
    {
        return isset($this->policy[$offset]) ? $this->policy[$offset] : null;
    }
}
