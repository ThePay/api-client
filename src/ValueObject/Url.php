<?php

declare(strict_types=1);

namespace ThePay\ApiClient\ValueObject;

class Url extends NonEmptyString
{
    protected static function filter($value)
    {
        $nonEmptyString = parent::filter($value);
        $urlCandidate = filter_var($nonEmptyString, FILTER_VALIDATE_URL);

        if ($urlCandidate === false) {
            throw self::invalidValue('URL', $nonEmptyString);
        }

        return $urlCandidate;
    }
}
