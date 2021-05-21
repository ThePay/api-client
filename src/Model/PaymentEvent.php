<?php

namespace ThePay\ApiClient\Model;

use DateTime;
use ThePay\ApiClient\Utils\Json;

class PaymentEvent
{
    const METHOD_SELECTION = 'method_selection';
    const STATE_CHANGE = 'state_change';
    const UNAVAILABLE_METHOD = 'unavailable_method';
    const PAYMENT_CANCELLED = 'payment_cancelled';
    const PAYMENT_ERROR = 'payment_error';

    /**
     * @var Datetime
     */
    private $occuredAt;

    /** @var string */
    private $type;

    /** @var string|null */
    private $data;

    /**
     * @param string|array $values Json in string or associative array
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
     * @return array The associative array of all parameters
     */
    public function toArray()
    {
        return array(
            'occuredAt' => $this->occuredAt,
            'type' => $this->type,
            'data' => $this->data,
        );
    }
}
