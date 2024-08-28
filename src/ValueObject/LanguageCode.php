<?php

declare(strict_types=1);

namespace ThePay\ApiClient\ValueObject;

class LanguageCode extends NonEmptyString
{
    protected static function filter($value)
    {
        $nonEmptyString = parent::filter($value);
        $languageCodeCandidate = trim($nonEmptyString);

        if (preg_match('/^[a-z]{2}$/', $languageCodeCandidate) !== 1) {
            throw self::invalidValue('ISO 6391 language code', $nonEmptyString);
        }

        return $languageCodeCandidate;
    }
}
