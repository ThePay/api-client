<?php

namespace ThePay\ApiClient\ValueObject;

interface ValueObject
{
    public function __construct($value);

    /**
     * @return string
     */
    public function __toString();

    public function getValue();

    /**
     * @return bool
     */
    public function equals(ValueObject $object);

    /**
     * @param mixed $value
     * @return ValueObject
     */
    public static function create($value);
}
