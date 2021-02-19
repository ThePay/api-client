<?php

namespace ThePay\ApiClient\ValueObject;

abstract class EnumValueObject extends BaseValueObject
{
    /** @var string */
    private $value;

    /**
     * @param string $value
     */
    public function __construct($value)
    {
        if ( ! in_array($value, static::getOptions(), true)) {
            throw new \InvalidArgumentException(sprintf('%s in not valid value', $value));
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

    /**
     * @return string[]
     */
    abstract public static function getOptions();
}
