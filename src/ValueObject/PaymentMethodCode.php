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
    const CARD = 'card';
    /**
     * @deprecated will be removed, for customer method selection in payment process use: ThePay\ApiClient\TheClient::getActivePaymentMethods
     */
    const PLATBA_24 = 'platba_24';
    /**
     * @deprecated will be removed, for customer method selection in payment process use: ThePay\ApiClient\TheClient::getActivePaymentMethods
     */
    const MOJEPLATBA = 'mojeplatba';
    /**
     * @deprecated will be removed, for customer method selection in payment process use: ThePay\ApiClient\TheClient::getActivePaymentMethods
     */
    const CSOB = 'csob';
    /**
     * @deprecated will be removed, for customer method selection in payment process use: ThePay\ApiClient\TheClient::getActivePaymentMethods
     */
    const POSTOVNI_SPORITELNA = 'postovni_sporitelna';
    /**
     * @deprecated will be removed, for customer method selection in payment process use: ThePay\ApiClient\TheClient::getActivePaymentMethods
     */
    const EKONTO = 'ekonto';
    /**
     * @deprecated will be removed, for customer method selection in payment process use: ThePay\ApiClient\TheClient::getActivePaymentMethods
     */
    const UNI_CREDIT = 'uni_credit';
    /**
     * @deprecated will be removed, for customer method selection in payment process use: ThePay\ApiClient\TheClient::getActivePaymentMethods
     */
    const MBANK = 'mbank';
    /**
     * @deprecated will be removed, for customer method selection in payment process use: ThePay\ApiClient\TheClient::getActivePaymentMethods
     */
    const FIO_BANKA = 'fio_banka';
    /**
     * @deprecated will be removed, for customer method selection in payment process use: ThePay\ApiClient\TheClient::getActivePaymentMethods
     */
    const MONETA = 'moneta';
    /**
     * @deprecated will be removed, for customer method selection in payment process use: ThePay\ApiClient\TheClient::getActivePaymentMethods
     */
    const EQUA_BANK = 'equa_bank';
    /**
     * @deprecated will be removed, for customer method selection in payment process use: ThePay\ApiClient\TheClient::getActivePaymentMethods
     */
    const TRANSFER = 'transfer';
    /**
     * @deprecated will be removed, for customer method selection in payment process use: ThePay\ApiClient\TheClient::getActivePaymentMethods
     */
    const BITCOIN = 'bitcoin';
    /**
     * @deprecated will be removed, for customer method selection in payment process use: ThePay\ApiClient\TheClient::getActivePaymentMethods
     */
    const PLATIMPAK = 'platimpak';

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
        return array(
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
        );
    }
}
