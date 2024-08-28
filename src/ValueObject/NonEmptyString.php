<?php

declare(strict_types=1);

namespace ThePay\ApiClient\ValueObject;

class NonEmptyString extends StringValue
{
    protected static function filter($value)
    {
        $nonEmptyStringCandidate = parent::filter($value);

        if (trim($nonEmptyStringCandidate) === '') {
            throw self::invalidValue('non-empty string', $nonEmptyStringCandidate);
        }

        return $nonEmptyStringCandidate;
    }
}
