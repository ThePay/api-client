<?php


namespace ThePay\ApiClient\ValueObject;

abstract class BaseValueObject implements ValueObject
{
    /**
     * BaseValueObject constructor.
     * @param mixed $value
     */
    abstract public function __construct($value);

    /**
     * @param mixed $value
     * @return static
     */
    public static function create($value)
    {
        return new static($value);
    }

    /**
     * @return bool
     */
    public function equals(ValueObject $object)
    {
        if (get_class($object) !== get_class($this)) {
            return false;
        }

        return $this->getValue() === $object->getValue();
    }
}
