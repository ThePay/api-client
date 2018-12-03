<?php

namespace ThePay\ApiClient\Filter;

use ThePay\ApiClient\Model\SignableRequest;
use ThePay\ApiClient\ValueObject\Amount;
use ThePay\ApiClient\ValueObject\CurrencyCode;
use ThePay\ApiClient\ValueObject\PaymentMethodCode;
use ThePay\ApiClient\ValueObject\PaymentState;

class PaymentsFilter implements SignableRequest
{
    /** @var \DateTime|null */
    private $createdFrom;

    /** @var \DateTime|null */
    private $createdTo;

    /** @var \DateTime|null */
    private $finishedFrom;

    /** @var \DateTime|null */
    private $finishedTo;

    /** @var PaymentMethodCode|null */
    private $paymentMethod;

    /** @var PaymentState|null */
    private $state;

    /** @var CurrencyCode|null */
    private $currency;

    /** @var Amount|null */
    private $amountFrom;

    /** @var Amount|null */
    private $amountTo;

    /**
     * @param \DateTime         $createdFrom
     * @param \DateTime         $createdTo
     * @param \DateTime         $finishedFrom
     * @param \DateTime         $finishedTo
     * @param PaymentMethodCode $paymentMethod
     * @param PaymentState      $state
     * @param CurrencyCode      $currency
     * @param Amount            $amountFrom
     * @param Amount            $amountTo
     */
    public function __construct(
        \DateTime $createdFrom = null,
        \DateTime$createdTo = null,
        \DateTime $finishedFrom= null,
        \DateTime $finishedTo = null,
        PaymentMethodCode $paymentMethod = null,
        PaymentState $state = null,
        CurrencyCode $currency = null,
        Amount $amountFrom = null,
        Amount $amountTo = null
    ) {
        $this->createdFrom = $createdFrom;
        $this->createdTo = $createdTo;
        $this->finishedFrom = $finishedFrom;
        $this->finishedTo = $finishedTo;
        $this->paymentMethod = $paymentMethod;
        $this->state = $state;
        $this->currency = $currency;
        $this->amountFrom = $amountFrom;
        $this->amountTo = $amountTo;
    }

    public function getQueryParams()
    {
        return $this->toArray();
    }


    /**
     * @return array
     */
    public function toArray()
    {
        $res =  array(
            'created_from' => $this->createdFrom,
            'created_to' => $this->createdTo,
            'finished_from' => $this->finishedFrom,
            'finished_to' => $this->finishedTo,
            'payment_method' => (string) $this->paymentMethod,
            'state' => (string) $this->state,
            'currency' => (string) $this->currency,
            'amount_from' => $this->amountFrom,
            'amount_to' => $this->amountTo,
        );

        foreach ($res as $k => $v) {
            if ($v === '') {
                unset($res[$k]);
            }
        }

        return $res;
    }
}
