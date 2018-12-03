<?php

namespace ThePay\ApiClient\Model;

use ThePay\ApiClient\Utils\Json;

class RealizeRecurringPaymentResponse
{
    /** @var string */
    private $state;

    /** @var string */
    private $message;

    /**
     * @param string|array $values
     */
    public function __construct($values)
    {
        $data = is_array($values) ? $values : Json::decode($values, true);

        $this->state = $data['state'];
        $this->message = $data['message'];
    }

    /**
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }
}
