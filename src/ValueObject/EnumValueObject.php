<?php

declare(strict_types=1);

namespace ThePay\ApiClient\ValueObject;

/**
 * @note all subclasses should be final / you can not inherit from enum
 */
abstract class EnumValueObject extends StringValue
{
    /**
     * @return string[]
     */
    abstract public static function cases(): array;

    protected static function filter($value)
    {
        $caseCandidate = parent::filter($value);

        if ( ! in_array($caseCandidate, static::cases(), true)) {
            throw self::invalidValue('case', $caseCandidate);
        }

        return $caseCandidate;
    }
}
