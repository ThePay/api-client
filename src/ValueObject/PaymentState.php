<?php

namespace ThePay\ApiClient\ValueObject;

class PaymentState extends EnumValueObject
{
    public const EXPIRED = 'expired';
    public const PAID = 'paid';
    public const PARTIALLY_REFUNDED = 'partially_refunded';
    public const REFUNDED = 'refunded';
    public const PREAUTHORIZED = 'preauthorized';
    public const PREAUTH_CANCELLED = 'preauth_cancelled';
    public const PREAUTH_EXPIRED = 'preauth_expired';
    public const WAITING_FOR_PAYMENT = 'waiting_for_payment';
    public const WAITING_FOR_CONFIRMATION = 'waiting_for_confirmation';

    /**
     * @return string[]
     */
    public static function getOptions()
    {
        return [
            self::EXPIRED,
            self::PAID,
            self::PARTIALLY_REFUNDED,
            self::REFUNDED,
            self::PREAUTHORIZED,
            self::PREAUTH_CANCELLED,
            self::PREAUTH_EXPIRED,
            self::WAITING_FOR_PAYMENT,
            self::WAITING_FOR_CONFIRMATION,
        ];
    }
}
