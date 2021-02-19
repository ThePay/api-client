<?php

namespace ThePay\ApiClient\ValueObject;

class PaymentState extends EnumValueObject
{
    const EXPIRED = 'expired';
    const PAID = 'paid';
    const PARTIALLY_REFUNDED = 'partially_refunded';
    const REFUNDED = 'refunded';
    const PREAUTHORIZED = 'preauthorized';
    const WAITING_FOR_PAYMENT = 'waiting_for_payment';
    const PREAUTH_CANCELLED = 'preauth_cancelled';
    const PREAUTH_EXPIRED = 'preauth_expired';

    /**
     * @return string[]
     */
    public static function getOptions()
    {
        return array(
            self::EXPIRED,
            self::PAID,
            self::PARTIALLY_REFUNDED,
            self::REFUNDED,
            self::PREAUTHORIZED,
            self::WAITING_FOR_PAYMENT,
            self::PREAUTH_CANCELLED,
            self::PREAUTH_EXPIRED,
        );
    }
}
