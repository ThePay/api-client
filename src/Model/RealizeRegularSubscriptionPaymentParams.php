<?php

namespace ThePay\ApiClient\Model;

use ThePay\ApiClient\ValueObject\Identifier;

class RealizeRegularSubscriptionPaymentParams extends RealizeSubscriptionPaymentParams
{
    /**
     * RealizeRegularSubscriptionPaymentParams constructor.
     *
     * @param string $uid
     */
    public function __construct($uid)
    {
        $this->uid = new Identifier($uid);
    }
}
