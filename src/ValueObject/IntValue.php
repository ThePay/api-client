<?php

declare(strict_types=1);

namespace ThePay\ApiClient\ValueObject;

/**
 * @extends BaseValueObject<int>
 */
class IntValue extends BaseValueObject
{
    public function __toString()
    {
        return (string) $this->value;
    }

    protected static function filter($value)
    {
        $intCandidate = filter_var($value, FILTER_VALIDATE_INT);

        if ($intCandidate === false) {
            throw self::invalidValue('int');
        }

        return $intCandidate;
    }
}
