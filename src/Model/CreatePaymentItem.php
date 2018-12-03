<?php


namespace ThePay\ApiClient\Model;

use ThePay\ApiClient\ValueObject\Amount;

class CreatePaymentItem
{
    /** @var string */
    private $type;

    /** @var string */
    private $name;

    /** @var Amount */
    private $price;

    /** @var string|null */
    private $ean;

    /** @var int */
    private $count;

    public function __construct($type, $name, $price, $count, $ean = null)
    {
        $this->type = $type;
        $this->name = $name;
        $this->price = $price;
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
            'count' => $this->count
        );
    }
}
