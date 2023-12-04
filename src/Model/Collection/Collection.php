<?php

namespace ThePay\ApiClient\Model\Collection;

/**
 * @template TValue
 * @implements \ArrayAccess<string|int, TValue>
 * @implements \Iterator<TValue>
 */
abstract class Collection implements \ArrayAccess, \Iterator
{
    /** @var array<TValue> */
    protected $data = array();

    /** @var int */
    private $iteratorPosition = 0;

    /**
     * @return array<TValue>
     */
    public function all()
    {
        return $this->data;
    }

    /**
     * @param TValue $item
     * @return void
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

    /**
     * @param string|int|null $offset
     * @param TValue $value
     * @return void
     */
    #[\ReturnTypeWillChange]
    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->data[] = $value;
        } else {
            $this->data[$offset] = $value;
        }
    }

    /**
     * @param string|int $offset
     * @return bool
     */
    #[\ReturnTypeWillChange]
    public function offsetExists($offset)
    {
        return isset($this->data[$offset]);
    }

    /**
     * @param string|int $offset
     */
    #[\ReturnTypeWillChange]
    public function offsetUnset($offset)
    {
        unset($this->data[$offset]);
    }

    /**
     * @param string|int $offset
     * @return TValue|null
     */
    #[\ReturnTypeWillChange]
    public function offsetGet($offset)
    {
        return isset($this->data[$offset]) ? $this->data[$offset] : null;
    }

    /**
     * @return void
     */
    #[\ReturnTypeWillChange]
    public function rewind()
    {
        $this->iteratorPosition = 0;
    }

    /**
     * @return TValue
     */
    #[\ReturnTypeWillChange]
    public function current()
    {
        return $this->data[$this->iteratorPosition];
    }

    /**
     * @return int
     */
    #[\ReturnTypeWillChange]
    public function key()
    {
        return $this->iteratorPosition;
    }

    /**
     * @return void
     */
    #[\ReturnTypeWillChange]
    public function next()
    {
        ++$this->iteratorPosition;
    }

    /**
     * @return bool
     */
    #[\ReturnTypeWillChange]
    public function valid()
    {
        return isset($this->data[$this->iteratorPosition]);
    }
}
