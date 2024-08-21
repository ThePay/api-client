<?php

declare(strict_types=1);

namespace ThePay\ApiClient\ValueObject;

class CurrencyCode extends NonEmptyString
{
    protected static function filter($value)
    {
        $nonEmptyString = parent::filter($value);
        $currencyCodeCandidate = trim($nonEmptyString);

        if (preg_match('/^[A-Z]{3}$/', $currencyCodeCandidate) !== 1) {
            throw self::invalidValue('ISO 4217 currency code', $nonEmptyString);
        }

        return $currencyCodeCandidate;
    }
}
