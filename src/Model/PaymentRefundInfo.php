<?php

namespace ThePay\ApiClient\Model;

use ThePay\ApiClient\ValueObject\Amount;
use ThePay\ApiClient\ValueObject\CurrencyCode;

final class PaymentRefundInfo
{
    /** @var Amount */
    private $availableAmount;

    /** @var CurrencyCode */
    private $currencyCode;

    /** @var PaymentRefund[] */
    private $partialRefunds;

    /**
     * @param int $availableAmount
     * @param string $currencyCode
     * @param PaymentRefund[] $partialRefunds
     */
    public function __construct($availableAmount, $currencyCode, array $partialRefunds)
    {
        $this->availableAmount = new Amount($availableAmount);
        $this->currencyCode = new CurrencyCode($currencyCode);
        $this->partialRefunds = $partialRefunds;
    }

    /**
     * @return int
     */
    public function getAvailableAmount()
    {
        return $this->availableAmount->getValue();
    }

    /**
     * @return string
     */
    public function getCurrencyCode()
    {
        return $this->currencyCode->getValue();
    }

    /**
     * @return PaymentRefund[]
     */
    public function getPartialRefunds()
    {
        return $this->partialRefunds;
    }
}
