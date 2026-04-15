<?php

declare(strict_types=1);

namespace ThePay\ApiClient\ValueObject;

final class GPCPaymentIdentifier extends EnumValueObject
{
    public const ORDER_NUMBER = 'order_number';
    public const UID = 'uid';
    public const ID = 'id';

    /**
     * @return array<string>
     */
    public static function cases(): array
    {
        return [
            self::ORDER_NUMBER,
            self::UID,
            self::ID,
        ];
    }
}
