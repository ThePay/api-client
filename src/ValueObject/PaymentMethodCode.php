<?php

namespace ThePay\ApiClient\ValueObject;

class PaymentMethodCode extends EnumValueObject
{
    const TEST_ONLINE = 'test_online';
    const TEST_OFFLINE = 'test_offline';
    const CARD = 'card';
    const TRANSFER = 'transfer';
    const PLATBA_24 = 'platba_24';
    const EKONTO = 'ekonto';
    const UNI_CREDIT = 'uni_credit';
    const BITCOIN = 'bitcoin';
    const CSOB = 'csob';
    const EQUA_BANK = 'equa_bank';
    const FIO_BANKA = 'fio_banka';
    const MOJEPLATBA = 'mojeplatba';
    const MONETA = 'moneta';
    const MBANK = 'mbank';

    /**
     * @return string[]
     */
    protected function getOptions()
    {
        return array(
            self::TEST_ONLINE,
            self::TEST_OFFLINE,
            self::CARD,
            self::TRANSFER,
            self::PLATBA_24,
            self::EKONTO,
            self::UNI_CREDIT,
            self::BITCOIN,
            self::CSOB,
            self::EQUA_BANK,
            self::FIO_BANKA,
            self::MOJEPLATBA,
            self::MONETA,
            self::MBANK,
        );
    }
}
