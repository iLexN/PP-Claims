<?php

namespace PP\WebPortal\Module\Model\AbstractClass;

use PP\WebPortal\Module\Model\Interfaces\ModelInterface;

abstract class ModelAbstract implements \ArrayAccess, ModelInterface
{
    /**
     * data info.
     *
     * @var array
     */
    protected $data;

    public function toArray()
    {
        return $this->data;
    }

    public function toJson()
    {
        return json_encode($this->data);
    }

    public function offsetSet($offset, $value)
    {
        $this->data[$offset] = $value;
    }

    public function offsetExists($offset)
    {
        return isset($this->data[$offset]);
    }

    public function offsetUnset($offset)
    {
        unset($this->data[$offset]);
    }

    public function offsetGet($offset)
    {
        return isset($this->data[$offset]) ? $this->data[$offset] : null;
    }
}
