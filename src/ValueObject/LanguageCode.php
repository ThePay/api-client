<?php

namespace ThePay\ApiClient\ValueObject;

use InvalidArgumentException;

final class LanguageCode extends BaseValueObject
{
    /** @var string */
    private $value;

    /**
     * CurrencyCode constructor.
     *
     * @param string $value
     */
    public function __construct($value)
    {
        $value = trim($value);

        if (strlen($value) !== 2) {
            throw new InvalidArgumentException('Value `' . $value . '` is not valid ISO 6391 language code');
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
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }
}
