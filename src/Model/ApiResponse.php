<?php

namespace ThePay\ApiClient\Model;

use ThePay\ApiClient\Utils\Json;

class ApiResponse
{
    /** @var string|null */
    private $state;

    /** @var string|null */
    private $message;

    /** @var int */
    private $statusCode;

    /**
     * ApiResponse constructor.
     *
     * @param string|array $values
     * @param int $statusCode
     */
    public function __construct($values, $statusCode)
    {
        $data = is_array($values) ? $values : Json::decode($values, true);

        $this->state = isset($data['state']) ? $data['state'] : null;
        $this->message = isset($data['message']) ? $data['message'] : null;
        $this->statusCode = $statusCode;
    }

    /**
     * @return string|null
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @return string|null
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * Determines if the subscription payment was realized.
     *
     * @return bool
     */
    public function wasSuccessful()
    {
        return $this->statusCode === 200 && $this->state === 'success';
    }
}
