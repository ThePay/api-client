<?php

namespace ThePay\ApiClient\Model;

use ThePay\ApiClient\ValueObject\Amount;
use ThePay\ApiClient\ValueObject\Identifier;

abstract class RealizeSubscriptionPaymentParams implements SignableRequest
{
    /** @var Amount|null */
    protected $amount;

    /** @var CreatePaymentItem[] */
    protected $items = [];

    /** @var Identifier */
    protected $uid;

    /** @var string|null */
    protected $orderId = null;

    /** @var string|null */
    protected $descriptionForMerchant = null;

    /** @var string|null */
    protected $notifUrl = null;

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
     * @return array<CreatePaymentItem>
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @return string|null
     */
    public function getOrderId()
    {
        return $this->orderId;
    }

    /**
     * @return string|null
     */
    public function getDescriptionForMerchant()
    {
        return $this->descriptionForMerchant;
    }

    /**
     * @return string|null
     */
    public function getNotifUrl()
    {
        return $this->notifUrl;
    }

    /**
     * @param string|null $notifUrl
     * @return RealizeSubscriptionPaymentParams
     */
    public function setNotifUrl($notifUrl)
    {
        $this->notifUrl = $notifUrl;
        return $this;
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
     * @return array<string, mixed>
     */
    public function toArray()
    {
        $result = [
            'uid' => $this->uid->getValue(),
            'items' => null,
            'order_id' => $this->orderId,
            'description_for_merchant' => $this->descriptionForMerchant,
        ];

        if ($this->items) {
            foreach ($this->items as $item) {
                $result['items'][] = $item->toArray();
            }
        }

        if ($this->amount) {
            $result['amount'] = (string) $this->amount->getValue();
        }

        if ($this->notifUrl !== null) {
            $result['notif_url'] = $this->notifUrl;
        }

        return $result;
    }
}
