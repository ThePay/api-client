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
    private ?DateTime $expiration_date = null;
    private ?string $brand;
    private string $type;

    /**
     * @param string|array{number:string,expiration_date:DateTime,brand:string,type:self::TYPE_*} $values Json in string or associative array
     */
    public function __construct($values)
    {
        $data = is_array($values) ? $values : Json::decode($values, true);

        $this->number = $data['number'];
        if ($data['expiration_date'] !== null) {
            $date = new DateTime($data['expiration_date']);
            $date->modify('last day of this month');
            $date->setTime(23, 59, 59, 999999);
            $this->expiration_date = $date;
        }
        $this->brand = $data['brand'];
        $type = $data['type'];
        if (in_array($type, [self::TYPE_CREDIT, self::TYPE_DEBIT, self::TYPE_PREPAID, self::TYPE_UNKNOWN])) {
            $this->type = $type;
        } else {
            throw new \RuntimeException('Invalid value for type: ' . $type);
        }
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
     * @return array{number:string,expiration_date:DateTime|null,brand:string,type:self::TYPE_*}
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
