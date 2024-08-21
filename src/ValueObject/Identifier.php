<?php

declare(strict_types=1);

namespace ThePay\ApiClient\ValueObject;

class Identifier extends NonEmptyString
{
    public const STRLEN_MAX = 100;

    protected static function filter($value)
    {
        $nonEmptyString = parent::filter($value);
        $identifierCandidate = trim($nonEmptyString);

        if (strlen($identifierCandidate) > self::STRLEN_MAX) {
            throw self::invalidValue(sprintf('up to %d characters', self::STRLEN_MAX), $nonEmptyString);
        }

        return $identifierCandidate;
    }
}
