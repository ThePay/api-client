<?php

namespace ThePay\ApiClient\ValueObject;

final class StringValue extends BaseValueObject
{
    /** @var string */
    private $value;

    public function __construct($value)
    {
        if ( ! is_string($value)) {
            throw new \InvalidArgumentException('type of value: '.(string)$value.' is not string');
        }

        $this->value = $value;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }
}
