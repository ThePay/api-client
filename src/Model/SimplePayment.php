<?php

namespace ThePay\ApiClient\Model;

use DateTime;
use Exception;
use ThePay\ApiClient\Utils\Json;

/**
 * Class SimplePayment
 *
 * @package ThePay\ApiClient\Model
 */
class SimplePayment
{

    /**
     * Unique ID of the payment
     * @var string
     */
    private $uid;

    /**
     * @var string
     */
    private $projectId;

    /**
     * Payment status (e.g. paid)
     * @var string
     */
    private $state;

    /**
     * Currency code (e.g. CZK)
     * @var string
     */
    private $currency;

    /**
     * @var int
     */
    private $amount;

    /**
     * @var DateTime
     */
    private $createdAt;

    /**
     * @var DateTime|null
     */
    private $finishedAt;

    /**
     * Payment method code
     * @var string|null
     */
    private $paymentMethod;

    /**
     * @param string|array $values Json in string or associative array
     * @throws Exception
     */
    public function __construct($values)
    {
        $data = is_array($values) ? $values : Json::decode($values, true);

        $this->uid = $data['uid'];
        $this->projectId = $data['project_id'];
        $this->state = $data['state'];
        $this->currency = $data['currency'];
        $this->amount = $data['amount'];
        $this->createdAt = new DateTime($data['created_at']);
        $this->finishedAt = $data['finished_at'] !== null ? new DateTime($data['finished_at']) : null;
        $this->paymentMethod = $data['payment_method'];
    }

    /**
     * @return string
     */
    public function getUid()
    {
        return $this->uid;
    }

    /**
     * @return string
     */
    public function getProjectId()
    {
        return $this->projectId;
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
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @return int
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @return DateTime|null
     */
    public function getFinishedAt()
    {
        return $this->finishedAt;
    }

    /**
     * @return string
     */
    public function getPaymentMethod()
    {
        return $this->paymentMethod;
    }

    /**
     * @return array The associative array of all parameters
     */
    public function toArray()
    {
        return array(
            'uid' => $this->uid,
            'projectId' => $this->projectId,
            'state' => $this->state,
            'currency' => $this->currency,
            'amount' => $this->amount,
            'createdAt' => $this->createdAt,
            'finishedAt' => $this->finishedAt,
            'paymentMethod' => $this->paymentMethod,
        );
    }
}
