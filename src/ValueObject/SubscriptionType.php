<?php

namespace ThePay\ApiClient\ValueObject;

class SubscriptionType extends EnumValueObject
{
    const REGULAR = 'regular';
    const USAGE_BASED = 'usagebased';
    const IRREGULAR = 'irregular';

    /**
     * @return string[]
     */
    public static function getOptions()
    {
        return array(
            self::REGULAR,
            self::USAGE_BASED,
            self::IRREGULAR,
        );
    }
}
