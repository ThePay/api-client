<?php

namespace ThePay\ApiClient\ValueObject;

/**
 * @extends BaseValueObject<string>
 */
class StringValue extends BaseValueObject
{
    public function __toString()
    {
        return $this->value;
    }

    protected static function filter($value)
    {
        if ( ! is_string($value)) {
            throw self::invalidValue('string');
        }

        return $value;
    }
}
