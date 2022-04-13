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
     * @var string|null
     */
    private $description_for_merchant;

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
     * @var array<PaymentEvent>
     */
    private $paymentEvents = array();

    /**
     * @param string|array<string, mixed> $values Json in string or associative array
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
        $this->validTo = new DateTime($data['valid_to']);
        $this->fee = $data['fee'];
        $this->description = $data['description'];
        $this->description_for_merchant = isset($data['description_for_merchant']) ? $data['description_for_merchant'] : null;
        $this->payUrl = $data['pay_url'];
        $this->detailUrl = $data['detail_url'];
        $this->orderId = $data['order_id'];
        $this->offsetAccount = $data['offset_account_status'] === 'loaded' ? new BankAccount($data['offset_account']) : null;
        $this->offsetAccountStatus = $data['offset_account_status'];
        $this->customer = $data['customer'] ? new Customer($data['customer']) : null;
        $this->offsetAccountDeterminedAt = $data['offset_account_determined_at'] !== null ? new \DateTime($data['offset_account_determined_at']) : null;

        if (count($data['events']) > 0) {
            foreach ($data['events'] as $eventData) {
                $this->paymentEvents[] = new PaymentEvent($eventData);
            }
        }
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
     * @return string|null
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return string|null
     */
    public function getDescriptionForMerchant()
    {
        return $this->description_for_merchant;
    }

    /**
     * @return string|null
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
     * @return array<PaymentEvent>
     */
    public function getEvents()
    {
        return $this->paymentEvents;
    }

    /**
     * @return bool
     */
    public function hasErrorOnLastAttempt()
    {
        return $this->wasEventTypeOnLastAttempt(PaymentEvent::PAYMENT_ERROR);
    }

    /**
     * @return bool
     */
    public function wasLastAttemptCancelledByUser()
    {
        return $this->wasEventTypeOnLastAttempt(PaymentEvent::PAYMENT_CANCELLED);
    }

    /**
     * @return array<string, mixed>
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
            'description_for_merchant' => $this->description_for_merchant,
            'paymentMethod' => $this->getPaymentMethod(),
            'payUrl' => $this->payUrl,
            'detailUrl' => $this->detailUrl,
            'customer' => $this->customer ? $this->customer->toArray() : null,
            'bankAccount' => $this->offsetAccount ? $this->offsetAccount->toArray() : null,
            'offsetAccountStatus' => $this->offsetAccountStatus,
            'offsetAccountDeterminedAt' => $this->offsetAccountDeterminedAt,
            'events' => $this->paymentEvents,
        );
    }

    /**
     * @return bool Returns true if the payment was paid
     */
    public function wasPaid()
    {
        return in_array($this->getState(), array('paid', 'partially_refunded', 'refunded'))
            && $this->getFinishedAt() != null;
    }

    /**
     * @param string $eventType
     * @return bool
     */
    private function wasEventTypeOnLastAttempt($eventType)
    {
        $events = array_reverse($this->getEvents());
        foreach ($events as $event) {
            if ($event->getType() === PaymentEvent::METHOD_SELECTION) {
                break;
            }

            if ($event->getType() === $eventType) {
                return true;
            }
        }

        return false;
    }
}
