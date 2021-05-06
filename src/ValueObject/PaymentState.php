<?php

namespace ThePay\ApiClient\ValueObject;

class PaymentState extends EnumValueObject
{
    const EXPIRED = 'expired';
    const PAID = 'paid';
    const PARTIALLY_REFUNDED = 'partially_refunded';
    const REFUNDED = 'refunded';
    const PREAUTHORIZED = 'preauthorized';
    const PREAUTH_CANCELLED = 'preauth_cancelled';
    const PREAUTH_EXPIRED = 'preauth_expired';
    const WAITING_FOR_PAYMENT = 'waiting_for_payment';
    const WAITING_FOR_CONFIRMATION = 'waiting_for_confirmation';

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
            self::PREAUTH_CANCELLED,
            self::PREAUTH_EXPIRED,
            self::WAITING_FOR_PAYMENT,
            self::WAITING_FOR_CONFIRMATION,
        );
    }
}
