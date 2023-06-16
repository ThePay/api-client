<?php

namespace ThePay\ApiClient\ValueObject;

class SubscriptionType extends EnumValueObject
{
    public const REGULAR = 'regular';
    public const USAGE_BASED = 'usagebased';
    public const IRREGULAR = 'irregular';

    /**
     * @return string[]
     */
    public static function getOptions()
    {
        return [
            self::REGULAR,
            self::USAGE_BASED,
            self::IRREGULAR,
        ];
    }
}
