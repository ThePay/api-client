<?php

namespace ThePay\ApiClient\Model;

use DateTime;
use Exception;
use ThePay\ApiClient\Utils\Json;

class Payment extends SimplePayment
{
    /**
     * @var string
     */
    private $orderId;

    /**
     * @var DateTime
     */
    private $validTo;

    /**
     * @var int
     */
    private $fee;

    /**
     * @var string|null
     */
    private $description;

    /**
     * @var string
     */
    private $payUrl;

    /**
     * @var string
     */
    private $detailUrl;

    /**
     * @var Customer|null
     */
    private $customer;

    /**
     * Information about bank account if available
     * @var BankAccount|null
     */
    private $offsetAccount;

    /**
     * @var string
     */
    private $offsetAccountStatus;

    /**
     * @var \Datetime|null
     */
    private $offsetAccountDeterminedAt;

    /**
     * @param string|array $values Json in string or associative array
     * @throws Exception
     */
    public function __construct($values)
    {
        parent::__construct($values);

        $data = is_array($values) ? $values : Json::decode($values, true);

        $this->validTo = new DateTime($data['valid_to']);
        $this->fee = $data['fee'];
        $this->description = $data['description'];
        $this->payUrl = $data['pay_url'];
        $this->detailUrl = $data['detail_url'];
        $this->orderId = $data['order_id'];
        $this->offsetAccount = $data['offset_account_status'] === 'loaded' ? new BankAccount($data['offset_account']) : null;
        $this->offsetAccountStatus = $data['offset_account_status'];
        $this->customer = $data['customer'] ? new Customer($data['customer']) : null;
        $this->offsetAccountDeterminedAt = $data['offset_account_determined_at'] !== null ? new \DateTime($data['offset_account_determined_at']) : null;
    }

    /**
     * @return DateTime
     */
    public function getValidTo()
    {
        return $this->validTo;
    }

    /**
     * @return int
     */
    public function getFee()
    {
        return $this->fee;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getOrderId()
    {
        return $this->orderId;
    }

    /**
     * @return BankAccount|null
     */
    public function getOffsetAccount()
    {
        return $this->offsetAccount;
    }

    /**
     * @return string
     */
    public function getPayUrl()
    {
        return $this->payUrl;
    }

    /**
     * @return string
     */
    public function getDetailUrl()
    {
        return $this->detailUrl;
    }

    /**
     * @return Customer
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * @return string
     */
    public function getOffsetAccountStatus()
    {
        return $this->offsetAccountStatus;
    }

    /**
     * @return Datetime|null
     */
    public function getOffsetAccountDeterminedAt()
    {
        return $this->offsetAccountDeterminedAt;
    }

    /**
     * @return array The associative array of all parameters
     */
    public function toArray()
    {
        return array(
            'uid' => $this->getUid(),
            'projectId' => $this->getProjectId(),
            'orderId' => $this->orderId,
            'state' => $this->getState(),
            'currency' => $this->getCurrency(),
            'amount' => $this->getAmount(),
            'createdAt' => $this->getCreatedAt(),
            'finishedAt' => $this->getFinishedAt(),
            'validTo' => $this->validTo,
            'fee' => $this->fee,
            'description' => $this->description,
            'paymentMethod' => $this->getPaymentMethod(),
            'payUrl' => $this->payUrl,
            'detailUrl' => $this->detailUrl,
            'customer' => $this->customer ? $this->customer->toArray() : null,
            'bankAccount' => $this->offsetAccount ? $this->offsetAccount->toArray() : null,
            'offsetAccountStatus' => $this->offsetAccountStatus,
            'offsetAccountDeterminedAt' => $this->offsetAccountDeterminedAt,
        );
    }
}
