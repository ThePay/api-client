<?php

namespace ThePay\ApiClient\ValueObject;

/**
 * @see https://dataapi21.docs.apiary.io/#introduction/enums/payment-method-tags
 */
final class PaymentMethodTag extends EnumValueObject
{
    public const RETURNABLE = 'returnable';
    public const PRE_AUTHORIZATION = 'pre_authorization';
    public const RECURRING_PAYMENTS = 'recurring_payments';
    public const ACCESS_ACCOUNT_OWNER = 'access_account_owner';
    public const ONLINE = 'online';
    public const CARD = 'card';
    public const BANK_TRANSFER = 'bank_transfer';
    public const ALTERNATIVE_METHOD = 'alternative_method';
    public const DEFERRED_PAYMENT = 'deferred_payment';

    /**
     * @return string[]
     */
    public static function getOptions()
    {
        return [
            self::RETURNABLE,
            self::PRE_AUTHORIZATION,
            self::RECURRING_PAYMENTS,
            self::ACCESS_ACCOUNT_OWNER,
            self::ONLINE,
            self::CARD,
            self::BANK_TRANSFER,
            self::ALTERNATIVE_METHOD,
            self::DEFERRED_PAYMENT,
        ];
    }
}
