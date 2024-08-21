<?php

declare(strict_types=1);

namespace ThePay\ApiClient\ValueObject;

class SecureUrl extends Url
{
    protected static function filter($value)
    {
        $secureUrlCandidate = parent::filter($value);

        if (strpos($secureUrlCandidate, 'https://') !== 0) {
            throw self::invalidValue('"https://" prefixed URL', $secureUrlCandidate);
        }

        return $secureUrlCandidate;
    }
}
