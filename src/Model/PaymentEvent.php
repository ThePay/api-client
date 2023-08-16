<?php

namespace ThePay\ApiClient\Model;

use DateTime;
use ThePay\ApiClient\Utils\Json;

class PaymentEvent
{
    public const METHOD_SELECTION = 'method_selection';
    public const STATE_CHANGE = 'state_change';
    public const UNAVAILABLE_METHOD = 'unavailable_method';
    public const PAYMENT_CANCELLED = 'payment_cancelled';
    public const PAYMENT_ERROR = 'payment_error';

    /**
     * @var Datetime
     */
    private $occuredAt;

    /** @var string */
    private $type;

    /** @var string|null */
    private $data;

    /**
     * @param string|array<string, mixed> $values Json in string or associative array
     */
    public function __construct($values)
    {
        $data = is_array($values) ? $values : Json::decode($values, true);

        $this->occuredAt = new DateTime($data['occured_at']);
        $this->data = $data['data'];
        $this->type = $data['type'];
    }

    /** @return Datetime */
    public function getOccuredAt()
    {
        return $this->occuredAt;
    }

    /** @return string */
    public function getType()
    {
        return $this->type;
    }

    /** @return string|null */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray()
    {
        return [
            'occuredAt' => $this->occuredAt,
            'type' => $this->type,
            'data' => $this->data,
        ];
    }
}
