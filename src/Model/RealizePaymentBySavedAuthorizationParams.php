<?php

namespace ThePay\ApiClient\Model;

use ThePay\ApiClient\ValueObject\Amount;
use ThePay\ApiClient\ValueObject\CurrencyCode;
use ThePay\ApiClient\ValueObject\Identifier;

final class RealizePaymentBySavedAuthorizationParams implements SignableRequest
{
    /** @var Amount */
    private $amount;

    /** @var CreatePaymentItem[] */
    private $items = [];

    /** @var Identifier */
    private $uid;

    /** @var CurrencyCode */
    private $currencyCode;

    /** @var string|null */
    protected $orderId = null;

    /** @var string|null */
    protected $descriptionForMerchant = null;

    protected ?string $notifUrl = null;

    /**
     * RealizePaymentBySavedAuthorizationParams constructor.
     *
     * @param string $uid
     * @param int $amount - payment amount in cents (required)
     * @param string $currencyCode - currency code (required)
     * @param string|null $orderId
     * @param string|null $descriptionForMerchant
     * @param string|null $notifUrl
     */
    public function __construct($uid, $amount, $currencyCode, $orderId = null, $descriptionForMerchant = null, ?string $notifUrl = null)
    {
        $this->uid = new Identifier($uid);
        $this->amount = new Amount($amount);
        $this->currencyCode = new CurrencyCode($currencyCode);
        $this->orderId = $orderId;
        $this->descriptionForMerchant = $descriptionForMerchant;
        $this->notifUrl = $notifUrl;
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
     * @return array<CreatePaymentItem>
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @return CurrencyCode
     */
    public function getCurrencyCode()
    {
        return $this->currencyCode;
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

    public function getNotifUrl(): ?string
    {
        return $this->notifUrl;
    }

    public function setNotifUrl(string $notifUrl): self
    {
        $this->notifUrl = $notifUrl;
        return $this;
    }

    /**
     * If no items will be set, the items from parent payment will be used.
     *
     * @param CreatePaymentItem $item
     * @return RealizePaymentBySavedAuthorizationParams
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
            'value' => [
                'amount' => (string) $this->amount->getValue(),
                'currency' => $this->currencyCode->getValue(),
            ],
            'order_id' => $this->orderId,
            'description_for_merchant' => $this->descriptionForMerchant,
        ];

        if ($this->items) {
            $result['items'] = [];
            foreach ($this->items as $item) {
                $result['items'][] = $item->toArray();
            }
        } else {
            $result['items'] = null;
        }

        if ($this->notifUrl !== null) {
            $result['notif_url'] = $this->notifUrl;
        }

        return $result;
    }
}
