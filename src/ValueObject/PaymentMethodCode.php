<?php

namespace ThePay\ApiClient\ValueObject;

class PaymentMethodCode extends EnumValueObject
{
    const CARD = 'card';
    const PLATBA_24 = 'platba_24';
    const MOJEPLATBA = 'mojeplatba';
    const CSOB = 'csob';
    const POSTOVNI_SPORITELNA = 'postovni_sporitelna';
    const EKONTO = 'ekonto';
    const UNI_CREDIT = 'uni_credit';
    const MBANK = 'mbank';
    const FIO_BANKA = 'fio_banka';
    const MONETA = 'moneta';
    const EQUA_BANK = 'equa_bank';
    const TRANSFER = 'transfer';
    const BITCOIN = 'bitcoin';
    const PLATIMPAK = 'platimpak';

    /**
     * @param string $paymentMethodCode
     */
    public function __construct($paymentMethodCode)
    {
        $this->value = $paymentMethodCode;
    }

    /**
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
