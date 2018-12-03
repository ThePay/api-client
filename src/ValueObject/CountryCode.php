<?php

namespace ThePay\ApiClient\ValueObject;

final class CountryCode extends BaseValueObject
{
    /** @var string */
    private $value;

    /**
     * @param string $value
     */
    public function __construct($value)
    {
        if ( ! is_string($value) || ! preg_match('/[A-Z]{2}/', $value)) {
            throw new \InvalidArgumentException('Value `' . $value . '` is not valid ISO 3166-1 (alpha-2) country code');
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
