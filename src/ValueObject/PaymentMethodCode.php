<?php

namespace ThePay\ApiClient\ValueObject;

/**
 * @deprecated will be removed, for customer method selection in payment process use: ThePay\ApiClient\TheClient::getActivePaymentMethods
 */
class PaymentMethodCode extends EnumValueObject
{
    /**
     * @deprecated will be removed, for customer method selection in payment process use: ThePay\ApiClient\TheClient::getActivePaymentMethods
     */
    public const CARD = 'card';
    /**
     * @deprecated will be removed, for customer method selection in payment process use: ThePay\ApiClient\TheClient::getActivePaymentMethods
     */
    public const PLATBA_24 = 'platba_24';
    /**
     * @deprecated will be removed, for customer method selection in payment process use: ThePay\ApiClient\TheClient::getActivePaymentMethods
     */
    public const MOJEPLATBA = 'mojeplatba';
    /**
     * @deprecated will be removed, for customer method selection in payment process use: ThePay\ApiClient\TheClient::getActivePaymentMethods
     */
    public const CSOB = 'csob';
    /**
     * @deprecated will be removed, for customer method selection in payment process use: ThePay\ApiClient\TheClient::getActivePaymentMethods
     */
    public const POSTOVNI_SPORITELNA = 'postovni_sporitelna';
    /**
     * @deprecated will be removed, for customer method selection in payment process use: ThePay\ApiClient\TheClient::getActivePaymentMethods
     */
    public const EKONTO = 'ekonto';
    /**
     * @deprecated will be removed, for customer method selection in payment process use: ThePay\ApiClient\TheClient::getActivePaymentMethods
     */
    public const UNI_CREDIT = 'uni_credit';
    /**
     * @deprecated will be removed, for customer method selection in payment process use: ThePay\ApiClient\TheClient::getActivePaymentMethods
     */
    public const MBANK = 'mbank';
    /**
     * @deprecated will be removed, for customer method selection in payment process use: ThePay\ApiClient\TheClient::getActivePaymentMethods
     */
    public const FIO_BANKA = 'fio_banka';
    /**
     * @deprecated will be removed, for customer method selection in payment process use: ThePay\ApiClient\TheClient::getActivePaymentMethods
     */
    public const MONETA = 'moneta';
    /**
     * @deprecated will be removed, for customer method selection in payment process use: ThePay\ApiClient\TheClient::getActivePaymentMethods
     */
    public const EQUA_BANK = 'equa_bank';
    /**
     * @deprecated will be removed, for customer method selection in payment process use: ThePay\ApiClient\TheClient::getActivePaymentMethods
     */
    public const TRANSFER = 'transfer';
    /**
     * @deprecated will be removed, for customer method selection in payment process use: ThePay\ApiClient\TheClient::getActivePaymentMethods
     */
    public const BITCOIN = 'bitcoin';
    /**
     * @deprecated will be removed, for customer method selection in payment process use: ThePay\ApiClient\TheClient::getActivePaymentMethods
     */
    public const PLATIMPAK = 'platimpak';

    /**
     * @deprecated will be removed, for customer method selection in payment process use: ThePay\ApiClient\TheClient::getActivePaymentMethods
     *
     * @param string $paymentMethodCode
     */
    public function __construct($paymentMethodCode)
    {
        $this->value = $paymentMethodCode;
    }

    /**
     * @deprecated will be removed, for customer method selection in payment process use: ThePay\ApiClient\TheClient::getActivePaymentMethods
     *
     * @return string[]
     */
    public static function getOptions()
    {
        return [
            self::CARD,
            self::PLATBA_24,
            self::MOJEPLATBA,
            self::CSOB,
            self::POSTOVNI_SPORITELNA,
            self::EKONTO,
            self::UNI_CREDIT,
            self::MBANK,
            self::FIO_BANKA,
            self::MONETA,
            self::EQUA_BANK,
            self::TRANSFER,
            self::BITCOIN,
            self::PLATIMPAK,
        ];
    }
}
