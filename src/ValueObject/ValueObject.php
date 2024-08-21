<?php

declare(strict_types=1);

namespace ThePay\ApiClient\ValueObject;

interface ValueObject
{
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
     *
     * @return static
     */
    public static function create($value);
}
