<?php


namespace ThePay\ApiClient\Model;

use ThePay\ApiClient\ValueObject\Amount;
use ThePay\ApiClient\ValueObject\CurrencyCode;
use ThePay\ApiClient\ValueObject\Identifier;

class CreateRecurringPaymentParams
{
    /** @var Identifier */
    private $parentUid;

    /** @var Identifier */
    private $uid;

    /** @var string */
    private $orderId;

    /** @var Amount */
    private $amount;

    /** @var CurrencyCode */
    private $currency;

    /** @var bool */
    private $isCustomerNotificationEnabled;

    /** @var CreatePaymentItem[] */
    private $items;

    /**
     * CreateReccuringPaymentParams constructor.
     *
     * @param string $uid
     * @param string $orderId
     * @param int    $amount
     * @param string $currency
     * @param bool   $isCustomerNotificationEnabled
     */
    public function __construct($parentUid, $uid, $orderId, $amount, $currency, $isCustomerNotificationEnabled = false)
    {
        $this->parentUid = Identifier::create($parentUid);
        $this->uid = Identifier::create($uid);
        $this->orderId = $orderId;
        $this->amount = Amount::create($amount);
        $this->currency = CurrencyCode::create($currency);
        $this->isCustomerNotificationEnabled = $isCustomerNotificationEnabled;
    }

    public function addItem(CreatePaymentItem $item)
    {
        $this->items[] = $item;
    }

    public function toArray()
    {
        $result = array(
            'uid' => (string) $this->uid->getValue(),
            'orderId' => (string) $this->orderId,
            'value' => array(
                'amount' => (string) $this->amount->getValue(),
                'currency' => $this->currency->getValue(),
            ),
            'isCustomerNotificationEnabled' => $this->isCustomerNotificationEnabled,
            'items' => null,
        );

        if ($this->items) {
            foreach ($this->items as $item) {
                $result['items'][] = $item->toArray();
            }
        }

        return $result;
    }

    /**
     * @return string
     */
    public function getParentUid()
    {
        return $this->parentUid->getValue();
    }
}
