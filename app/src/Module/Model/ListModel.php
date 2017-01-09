<?php

namespace PP\WebPortal\Module\Model;

use PP\WebPortal\Module\Model\Interfaces\ModelInterface;

class ListModel implements \Iterator, ModelInterface
{
    private $position = 0;

    /**
     * data info.
     *
     * @var array
     */
    protected $data;

    public function __construct()
    {
        $this->position = 0;
    }

    public function push(ModelInterface $obj)
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
