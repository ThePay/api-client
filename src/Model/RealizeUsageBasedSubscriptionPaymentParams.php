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
     * @param string|null $orderId
     * @param string|null $descriptionForMerchant
     */
    public function __construct($uid, $amount, $orderId = null, $descriptionForMerchant = null)
    {
        $this->uid = new Identifier($uid);
        $this->amount = new Amount($amount);
        $this->orderId = $orderId;
        $this->descriptionForMerchant = $descriptionForMerchant;
    }
}
