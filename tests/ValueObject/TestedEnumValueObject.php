<?php

declare(strict_types=1);

namespace ThePay\ApiClient\Tests\ValueObject;

use ThePay\ApiClient\ValueObject\EnumValueObject;

final class TestedEnumValueObject extends EnumValueObject
{
    public const CASE = 'case';

    public static function cases(): array
    {
        return [
            self::CASE,
        ];
    }
}
