<?php

namespace ThePay\ApiClient\ValueObject;

final class PaymentRefundState extends EnumValueObject
{
    public const WAITING = 'waiting';
    public const RETURNED = 'returned';
    public const DECLINED = 'declined';

    /**
     * @return string[]
     */
    public static function getOptions()
    {
        return [
            self::WAITING,
            self::RETURNED,
            self::DECLINED,
        ];
    }
}
