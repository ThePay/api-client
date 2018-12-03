<?php

namespace ThePay\ApiClient\ValueObject;

use InvalidArgumentException;

final class Identifier extends BaseValueObject
{
    /** @var string */
    private $value;

    /**
     * Uid constructor.
     *
     * @param string $value
     */
    public function __construct($value)
    {
        $value = trim($value);

        if (strlen($value) > 100) {
            throw new InvalidArgumentException('Value\'s length has to be up to 100 characters');
        }

        $this->value = (string)$value;
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
