<?php

namespace ThePay\ApiClient\ValueObject;

use InvalidArgumentException;

final class Amount extends BaseValueObject
{
    /** @var int */
    private $value;

    /**
     * Amount constructor.
     *
     * @param int $value
     */
    public function __construct($value)
    {
        if ( ! is_int($value)) {
            throw new InvalidArgumentException('Value has to be an integer.');
        }

        $this->value = $value;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->value;
    }

    /**
     * @return int
     */
    public function getValue()
    {
        return $this->value;
    }
}
