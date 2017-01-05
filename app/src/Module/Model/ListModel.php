<?php

namespace PP\WebPortal\Module\Model;

use PP\WebPortal\Module\Model\AbstractClass\ModelAbstract;
use PP\WebPortal\Module\Model\Interfaces\ModelInterfaces;

class ListModel implements \Iterator,ModelInterfaces
{
    private $position = 0;

    public function __construct()
    {
        $this->position = 0;
    }

    public function push(ModelInterfaces $obj)
    {
        $this->data[] = $obj;
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

    public function rewind()
    {
        $this->position = 0;
    }

    public function current()
    {
        return $this->data[$this->position];
    }

    public function key()
    {
        return $this->position;
    }

    public function next()
    {
        ++$this->position;
    }

    public function valid()
    {
        return isset($this->data[$this->position]);
    }
}
