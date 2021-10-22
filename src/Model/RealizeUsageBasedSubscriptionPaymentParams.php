<?php

namespace ThePay\ApiClient\Model;

use ThePay\ApiClient\ValueObject\Amount;
use ThePay\ApiClient\ValueObject\Identifier;

class RealizeUsageBasedSubscriptionPaymentParams extends RealizeSubscriptionPaymentParams
{
    /**
     * RealizeUsageBasedSubscriptionPaymentParams constructor.
     *
     * @param string $uid
     * @param int $amount - payment amount in cents
     */
    public function __construct($uid, $amount)
    {
        $this->uid = new Identifier($uid);
        $this->amount = new Amount($amount);
    }
}
