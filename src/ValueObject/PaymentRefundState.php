<?php

declare(strict_types=1);

namespace ThePay\ApiClient\ValueObject;

final class PaymentRefundState extends EnumValueObject
{
    public const WAITING = 'waiting';
    public const RETURNED = 'returned';
    public const DECLINED = 'declined';

    public static function cases(): array
    {
        return [
            self::WAITING,
            self::RETURNED,
            self::DECLINED,
        ];
    }
}
