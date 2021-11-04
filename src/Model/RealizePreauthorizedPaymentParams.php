<?php

namespace ThePay\ApiClient\Model;

use ThePay\ApiClient\ValueObject\Amount;
use ThePay\ApiClient\ValueObject\Identifier;

class RealizePreauthorizedPaymentParams implements SignableRequest
{
    /** @var Amount */
    private $amount;

    /** @var Identifier */
    private $uid;

    /**
     * @param int $amount
     * @param string $uid
     */
    public function __construct($amount, $uid)
    {
        $this->amount = new Amount($amount);
        $this->uid = new Identifier($uid);
    }

    /**
     * @return Amount
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @return Identifier
     */
    public function getUid()
    {
        return $this->uid;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray()
    {
        return array(
            'amount' => $this->amount->getValue(),
        );
    }
}
