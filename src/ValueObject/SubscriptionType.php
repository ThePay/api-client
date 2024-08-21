<?php

declare(strict_types=1);

namespace ThePay\ApiClient\ValueObject;

final class SubscriptionType extends EnumValueObject
{
    public const REGULAR = 'regular';
    public const USAGE_BASED = 'usagebased';
    public const IRREGULAR = 'irregular';

    public static function cases(): array
    {
        return [
            self::REGULAR,
            self::USAGE_BASED,
            self::IRREGULAR,
        ];
    }
}
