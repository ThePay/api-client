<?php

namespace ThePay\ApiClient\ValueObject;

class NonEmptyString extends StringValue
{
    protected static function filter($value)
    {
        $string = parent::filter($value);

        if (trim($string) === '') {
            throw self::invalidValue('non-empty string', $string);
        }

        return $string;
    }
}
