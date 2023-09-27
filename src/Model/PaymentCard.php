<?php

namespace ThePay\ApiClient\Model;

use DateTime;
use ThePay\ApiClient\Utils\Json;

class PaymentCard
{
    public const TYPE_CREDIT = 'credit';
    public const TYPE_DEBIT = 'debit';
    public const TYPE_PREPAID = 'prepaid';
    public const TYPE_UNKNOWN = 'unknown';

    private string $number;
    private ?DateTime $expiration_date;
    private ?string $brand;
    private string $type;

    /**
     * @param string|array<string,string> $values Json in string or associative array
     */
    public function __construct($values)
    {
        $data = is_array($values) ? $values : Json::decode($values, true);

        $this->number = $data['number'];
        $this->expiration_date = $data['expiration_date'] !== null ? new DateTime($data['expiration_date']) : null;
        $this->brand = $data['brand'];
        $this->type = $data['type'];
    }

    public function getNumber(): string
    {
        return $this->number;
    }

    public function getExpirationDate(): ?DateTime
    {
        return $this->expiration_date;
    }

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    /**
     * @return self::TYPE_*
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return array<string,string|DateTime>
     */
    public function toArray()
    {
        return [
            'number' => $this->number,
            'expiration_date' => $this->expiration_date,
            'brand' => $this->brand,
            'type' => $this->type,
        ];
    }
}
