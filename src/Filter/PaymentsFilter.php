<?php

namespace ThePay\ApiClient\Filter;

use ThePay\ApiClient\Model\SignableRequest;
use ThePay\ApiClient\ValueObject\Amount;
use ThePay\ApiClient\ValueObject\CurrencyCode;
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

    /** @var non-empty-string|null */
    private ?string $methodCode;

    /** @var PaymentState|null */
    private $state;

    /** @var CurrencyCode|null */
    private $currency;

    /** @var Amount|null */
    private $amountFrom;

    /** @var Amount|null */
    private $amountTo;

    /** @var string|null */
    private $orderId;

    /**
     * @param \DateTime|null $createdFrom
     * @param \DateTime|null $createdTo
     * @param \DateTime|null $finishedFrom
     * @param \DateTime|null $finishedTo
     * @param non-empty-string|null $methodCode
     * @param PaymentState|null $state
     * @param CurrencyCode|null $currency
     * @param Amount|null $amountFrom
     * @param Amount|null $amountTo
     * @param string|null $orderId
     */
    public function __construct(
        \DateTime $createdFrom = null,
        \DateTime $createdTo = null,
        \DateTime $finishedFrom = null,
        \DateTime $finishedTo = null,
        ?string $methodCode = null,
        PaymentState $state = null,
        CurrencyCode $currency = null,
        Amount $amountFrom = null,
        Amount $amountTo = null,
        $orderId = null
    ) {
        $this->createdFrom = $createdFrom;
        $this->createdTo = $createdTo;
        $this->finishedFrom = $finishedFrom;
        $this->finishedTo = $finishedTo;
        $this->methodCode = $methodCode;
        $this->state = $state;
        $this->currency = $currency;
        $this->amountFrom = $amountFrom;
        $this->amountTo = $amountTo;
        $this->orderId = $orderId;
    }

    /**
     * @return array<string, mixed>
     */
    public function getQueryParams()
    {
        return $this->toArray();
    }


    /**
     * @return array<string, mixed>
     */
    public function toArray()
    {
        $res = [
            'created_from' => $this->createdFrom,
            'created_to' => $this->createdTo,
            'finished_from' => $this->finishedFrom,
            'finished_to' => $this->finishedTo,
            'payment_method' => (string) $this->methodCode,
            'state' => (string) $this->state,
            'currency' => (string) $this->currency,
            'amount_from' => $this->amountFrom,
            'amount_to' => $this->amountTo,
            'order_id' => $this->orderId,
        ];

        foreach ($res as $k => $v) {
            if ($v === '') {
                unset($res[$k]);
            }
        }

        return $res;
    }
}
