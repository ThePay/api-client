<?php

namespace ThePay\ApiClient\Model;

use ThePay\ApiClient\ValueObject\Amount;
use ThePay\ApiClient\ValueObject\Identifier;

abstract class RealizeSubscriptionPaymentParams
{
    /** @var Amount|null */
    protected $amount;

    /** @var CreatePaymentItem[] */
    protected $items = array();

    /** @var Identifier */
    protected $uid;

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
     * @return array
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * If no items will be set, the items from parent payment will be used.
     *
     * @param CreatePaymentItem $item
     * @return RealizeSubscriptionPaymentParams
     */
    public function addItem(CreatePaymentItem $item)
    {
        $this->items[] = $item;

        return $this;
    }

    /**
     * @return array The associative array of all parameters for signing the request (interface SignableRequest)
     */
    public function toArray()
    {
        $result = array(
            'payment_uid' => $this->uid->getValue(),
            'items' => null,
        );

        if ($this->items) {
            foreach ($this->items as $item) {
                $result['items'][] = $item->toArray();
            }
        }

        if ($this->amount) {
            $result['amount'] = $this->amount->getValue();
        }

        return $result;
    }
}
