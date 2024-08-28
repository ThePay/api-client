<?php

declare(strict_types=1);

namespace ThePay\ApiClient\ValueObject;

class PhoneNumber extends NonEmptyString
{
    protected static function filter($value)
    {
        $nonEmptyString = parent::filter($value);
        $phoneNumberCandidate = ltrim(str_replace(' ', '', $nonEmptyString), '+0');

        if (preg_match('/^\d{1,15}$/', $phoneNumberCandidate) !== 1) {
            throw self::invalidValue('phone number in MSISDN format', $nonEmptyString);
        }

        return $phoneNumberCandidate;
    }
}
