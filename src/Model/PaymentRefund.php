<?php

namespace ThePay\ApiClient\Model;

use ThePay\ApiClient\ValueObject\Amount;
use ThePay\ApiClient\ValueObject\PaymentRefundState;
use ThePay\ApiClient\ValueObject\StringValue;

final class PaymentRefund
{
    /** @var Amount */
    private $amount;

    /** @var StringValue */
    private $reason;

    /** @var PaymentRefundState */
    private $state;

    /**
     * @param int $amount
     * @param string $reason
     * @param string $state
     */
    public function __construct($amount, $reason, $state)
    {
        $this->amount = new Amount($amount);
        $this->reason = new StringValue($reason);
        $this->state = new PaymentRefundState($state);
    }

    /**
     * @return int amount refunded in cents, currency for Payment Refund is same as currency of refunded Payment
     */
    public function getAmount()
    {
        return $this->amount->getValue();
    }

    /**
     * @return string
     */
    public function getReason()
    {
        return $this->reason->getValue();
    }

    /**
     * @return string
     */
    public function getState()
    {
        return $this->state->getValue();
    }
}
