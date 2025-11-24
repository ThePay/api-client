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

    /** @var array<string, mixed>|null */
    private $parent;

    /**
     * ApiResponse constructor.
     *
     * @param string|array<string, mixed> $values
     * @param int $statusCode
     */
    public function __construct($values, $statusCode)
    {
        $data = is_array($values) ? $values : Json::decode($values, true);

        $this->state = isset($data['state']) ? $data['state'] : null;
        $this->message = isset($data['message']) ? $data['message'] : null;
        $this->parent = isset($data['parent']) ? $data['parent'] : null;
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
     * Get parent payment information (for subscription and saved authorization payments).
     *
     * @return array<string, mixed>|null
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Check if recurring payments are still available for the parent payment.
     * Only applicable for subscription and saved authorization payments.
     *
     * @return bool|null Returns null if parent information is not available
     */
    public function isRecurringPaymentsAvailable()
    {
        if ($this->parent === null) {
            return null;
        }

        return isset($this->parent['recurring_payments_available'])
            ? (bool) $this->parent['recurring_payments_available']
            : null;
    }

    /**
     * Determines if the payment was realized successfully.
     *
     * @return bool|null Return null if the payment is still being processed asynchronously.
     */
    public function wasSuccessful()
    {
        if ($this->statusCode === 200 && $this->state === 'paid' || $this->state === 'success') {
            return true;
        }

        if ($this->statusCode === 202 && $this->state === 'waiting_for_confirmation') {
            return null;
        }

        return false;
    }
}
