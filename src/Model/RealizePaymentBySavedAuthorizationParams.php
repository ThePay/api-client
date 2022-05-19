<?php

namespace ThePay\ApiClient\Model;

use InvalidArgumentException;
use ThePay\ApiClient\ValueObject\Amount;
use ThePay\ApiClient\ValueObject\CurrencyCode;
use ThePay\ApiClient\ValueObject\Identifier;

final class RealizePaymentBySavedAuthorizationParams implements SignableRequest
{
    /** @var Amount|null */
    private $amount;

    /** @var CreatePaymentItem[] */
    private $items = array();

    /** @var Identifier */
    private $uid;

    /** @var CurrencyCode|null */
    private $currencyCode;

    /** @var string|null */
    protected $orderId = null;

    /** @var string|null */
    protected $descriptionForMerchant = null;

    /**
     * RealizePaymentBySavedAuthorizationParams constructor.
     *
     * @param string $uid
     * @param int|null $amount - payment amount in cents, if set to null it will use amount from parent payment, required if $currencyCode is present
     * @param string|null $currencyCode required if $amount is present
     * @param string|null $orderId
     * @param string|null $descriptionForMerchant
     */
    public function __construct($uid, $amount = null, $currencyCode = null, $orderId = null, $descriptionForMerchant = null)
    {
        if (($amount === null && $currencyCode !== null) || ($amount !== null && $currencyCode === null)) {
            throw new InvalidArgumentException('Amount and currency code is required if one of these parameters have value.');
        }
        $this->uid = new Identifier($uid);
        $this->amount = $amount === null ? null : new Amount($amount);
        $this->currencyCode = $currencyCode === null ? null : new CurrencyCode($currencyCode);
        $this->orderId = $orderId;
        $this->descriptionForMerchant = $descriptionForMerchant;
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
        $result = array(
            'uid' => $this->uid->getValue(),
            'items' => null,
            'orderId' => $this->orderId,
            'descriptionForMerchant' => $this->descriptionForMerchant,
        );

        if ($this->items) {
            foreach ($this->items as $item) {
                $result['items'][] = $item->toArray();
            }
        }

        if ($this->amount) {
            $result['value'] = array(
                'amount' => $this->amount->getValue(),
                'currency' => $this->currencyCode->getValue(),
            );
        }

        return $result;
    }
}
