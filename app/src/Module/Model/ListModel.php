<?php

namespace PP\WebPortal\Module\Model;

use PP\WebPortal\Module\Model\AbstractClass\ModelAbstract;
use PP\WebPortal\Module\Model\Interfaces\ModelInterface;

class ListModel extends ModelAbstract implements \IteratorAggregate
{
    /**
     * data info.
     *
     * @var array
     */
    public $data = [];

    public function __construct()
    {
    }

    public function push(ModelInterface $obj, $key = null)
    {
        if ($key === null) {
            $this->data[] = $obj;
        } else {
            $this->data[$obj->getKey()] = $obj;
        }
    }

    public function toArray()
    {
        $out = [];
        /* @var $obj ModelInterfaces */
        foreach ($this->data as $obj) {
            $out[] = $obj->toArray();
        }

        return $out;
    }

    public function toJson()
    {
        return json_encode($this->toArray());
    }

    /**
     * Get collection iterator.
     *
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->data);
    }
}
