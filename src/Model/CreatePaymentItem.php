<?php

namespace ThePay\ApiClient\Model;

use ThePay\ApiClient\ValueObject\Amount;

class CreatePaymentItem
{
    /** @var string one of {item, delivery, discount} */
    private $type;

    /** @var string */
    private $name;

    /** @var Amount price of all items combined */
    private $price;

    /** @var string|null */
    private $ean;

    /** @var int */
    private $count;

    /**
     * CreatePaymentItem constructor.
     *
     * @param string $type - one of {item, delivery, discount}
     * @param string $name
     * @param int|Amount $price - price for all items in cents
     * @param int $count - number of items
     * @param null $ean
     */
    public function __construct($type, $name, $price, $count, $ean = null)
    {
        $this->type = $type;
        $this->name = $name;
        $this->price = ! is_object($price) ? new Amount($price) : $price;
        $this->ean = $ean;
        $this->count = $count;
    }

    public function toArray()
    {
        return array(
            'type' => $this->type,
            'name' => $this->name,
            'price' => $this->price->getValue(),
            'ean' => $this->ean,
            'count' => $this->count,
            'total_price' => $this->price->getValue(),
        );
    }
}
