<?php

namespace ThePay\ApiClient\Filter;

use ThePay\ApiClient\Model\SignableRequest;
use ThePay\ApiClient\ValueObject\CurrencyCode;

class TransactionFilter implements SignableRequest
{
    /** @var string  */
    private $accountIban;

    /** @var \DateTime */
    private $dateFrom;

    /** @var \DateTime */
    private $dateTo;

    /** @var CurrencyCode|null */
    private $currencyCode;

    /**
     * TransactionFilter constructor.
     * @param string $accountIban
     * @param \DateTime $dateFrom
     * @param \DateTime $dateTo
     * @param string|null $currencyCode
     */
    public function __construct(
        $accountIban,
        \DateTime $dateFrom,
        \DateTime $dateTo,
        $currencyCode = null
    ) {
        $this->accountIban = $accountIban;
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
        $this->currencyCode = $currencyCode !== null ? new CurrencyCode($currencyCode) : null;
    }

    public function toArray()
    {
        $data = array(
            'account_iban' => $this->accountIban,
            'date_from' => $this->dateFrom,
            'date_to' => $this->dateTo,
        );

        if ($this->currencyCode !== null) {
            $data['currency_code'] = (string) $this->currencyCode;
        }

        return $data;
    }

    /**
     * @return string
     */
    public function getAccountIban()
    {
        return $this->accountIban;
    }

    /**
     * @return \DateTime
     */
    public function getDateFrom()
    {
        return $this->dateFrom;
    }

    /**
     * @return \DateTime
     */
    public function getDateTo()
    {
        return $this->dateTo;
    }

    /**
     * @return CurrencyCode|null
     */
    public function getCurrencyCode()
    {
        return $this->currencyCode;
    }
}
