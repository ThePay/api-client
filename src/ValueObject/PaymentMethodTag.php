<?php

namespace ThePay\ApiClient\ValueObject;

/**
 * @see https://dataapi21.docs.apiary.io/#introduction/enums/payment-method-tags
 */
final class PaymentMethodTag extends EnumValueObject
{
    const RETURNABLE = 'returnable';
    const PRE_AUTHORIZATION = 'pre_authorization';
    const RECURRING_PAYMENTS = 'recurring_payments';
    const ACCESS_ACCOUNT_OWNER = 'access_account_owner';
    const ONLINE = 'online';
    const CARD = 'card';
    const ALTERNATIVE_METHOD = 'alternative_method';

    /**
     * @return string[]
     */
    public static function getOptions()
    {
        return array(
            self::RETURNABLE,
            self::PRE_AUTHORIZATION,
            self::RECURRING_PAYMENTS,
            self::ACCESS_ACCOUNT_OWNER,
            self::ONLINE,
            self::CARD,
            self::ALTERNATIVE_METHOD,
        );
    }
}
