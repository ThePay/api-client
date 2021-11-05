<?php

namespace ThePay\ApiClient\ValueObject;

interface ValueObject
{
    /**
     * @param mixed $value
     */
    public function __construct($value);

    /**
     * @return string
     */
    public function __toString();

    /**
     * @return mixed
     */
    public function getValue();

    /**
     * @return bool
     */
    public function equals(ValueObject $object);

    /**
     * @param mixed $value
     * @return static
     */
    public static function create($value);
}
