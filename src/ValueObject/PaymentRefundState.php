<?php

namespace ThePay\ApiClient\ValueObject;

final class PaymentRefundState extends EnumValueObject
{
    const WAITING = 'waiting';
    const RETURNED = 'returned';
    const DECLINED = 'declined';

    /**
     * @return string[]
     */
    public static function getOptions()
    {
        return array(
            self::WAITING,
            self::RETURNED,
            self::DECLINED,
        );
    }
}
