<?php

namespace ThePay\ApiClient\Model;

use ThePay\ApiClient\ValueObject\Identifier;

class RealizeIrregularSubscriptionPaymentParams extends RealizeSubscriptionPaymentParams
{
    /**
     * RealizeIrregularSubscriptionPaymentParams constructor.
     *
     * @param string $uid
     * @param string|null $orderId
     * @param string|null $descriptionForMerchant
     */
    public function __construct($uid, $orderId = null, $descriptionForMerchant = null)
    {
        $this->uid = new Identifier($uid);
        $this->orderId = $orderId;
        $this->descriptionForMerchant = $descriptionForMerchant;
    }
}
