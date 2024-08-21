<?php

declare(strict_types=1);

namespace ThePay\ApiClient\ValueObject;

class CountryCode extends NonEmptyString
{
    protected static function filter($value)
    {
        $nonEmptyString = parent::filter($value);
        $countryCodeCandidate = trim($nonEmptyString);

        if (preg_match('/^[A-Z]{2}$/', $countryCodeCandidate) !== 1) {
            throw self::invalidValue('ISO 3166-1 (alpha-2) country code', $nonEmptyString);
        }

        return $countryCodeCandidate;
    }
}
