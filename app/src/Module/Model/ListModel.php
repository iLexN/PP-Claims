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

    private $first = 0;

    public function __construct()
    {
    }

    public function push(ModelInterface $obj, $key = null)
    {
        if ($key === null) {
            $this->data[] = $obj;
        } else {
            $this->setFirstKey($obj->getKey());
            $this->data[$obj->getKey()] = $obj;
        }
    }

    private function setFirstKey($k)
    {
        if ($this->first === 0) {
            $this->first = $k;
        }
    }

    public function getFirstData()
    {
        return $this->data[$this->first];
    }

    public function sortByIdFirst($id){
        uksort($this->data, function($a) use ($id){
            if ( $a === $id) {
                return -1;
            } else {
                return 1;
            }
        });
        $this->first = $id;
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
