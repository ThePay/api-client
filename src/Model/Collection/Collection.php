<?php

namespace ThePay\ApiClient\Model\Collection;

abstract class Collection implements \ArrayAccess, \Iterator
{

    /** @var array */
    protected $data = array();

    /** @var int */
    private $iteratorPosition = 0;

    /**
     * @return array
     */
    public function all()
    {
        return $this->data;
    }

    /**
     * @param mixed $item
     */
    public function add($item)
    {
        $this->data[] = $item;
    }

    /** @return int */
    public function size()
    {
        return count($this->data);
    }

    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->data[] = $value;
        } else {
            $this->data[$offset] = $value;
        }
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

    public function rewind()
    {
        $this->iteratorPosition = 0;
    }

    public function current()
    {
        return $this->data[$this->iteratorPosition];
    }

    public function key()
    {
        return $this->iteratorPosition;
    }

    public function next()
    {
        ++$this->iteratorPosition;
    }

    public function valid()
    {
        return isset($this->data[$this->iteratorPosition]);
    }
}
