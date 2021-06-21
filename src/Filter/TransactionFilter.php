<?php

namespace ThePay\ApiClient\Filter;

use ThePay\ApiClient\Model\SignableRequest;

class TransactionFilter implements SignableRequest
{
    /** @var string  */
    private $accountIban;

    /** @var \DateTime */
    private $dateFrom;

    /** @var \DateTime */
    private $dateTo;

    /**
     * TransactionFilter constructor.
     * @param string $accountIban
     * @param \DateTime $dateFrom
     * @param \DateTime $dateTo
     */
    public function __construct(
        $accountIban,
        \DateTime $dateFrom,
        \DateTime $dateTo
    ) {
        $this->accountIban = $accountIban;
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
    }

    public function toArray()
    {
        return array(
            'account_iban' => $this->accountIban,
            'date_from' => $this->dateFrom,
            'date_to' => $this->dateTo,
        );
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
}
