<?php

namespace PP\WebPortal\Module\Model;

use PP\WebPortal\Module\Model\AbstractClass\ModelAbstract;
use PP\WebPortal\Module\Model\Interfaces\ModelInterface;

class ListModel extends ModelAbstract implements \IteratorAggregate
{
    private $position = 0;

    /**
     * data info.
     *
     * @var array
     */
    public $data = [];

    public function __construct()
    {
        $this->position = 0;
    }

    public function push(ModelInterface $obj, $key = null)
    {
        if ($key === null) {
            $this->data[] = $obj;
        } else {
            error_log($obj->getKey());
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
     * Get collection iterator
     *
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->data);
    }
}
