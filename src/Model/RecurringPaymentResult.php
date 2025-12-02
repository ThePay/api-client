<?php

namespace ThePay\ApiClient\Model;

use ThePay\ApiClient\Utils\Json;

/**
 * Result of recurring payment realization (subscription or saved authorization).
 *
 * @see https://docs.thepay.eu/#tag/Subscriptions
 * @see https://docs.thepay.eu/#tag/Saved-Card-Authorization
 */
class RecurringPaymentResult
{
    /** @var string Payment was realized successfully (HTTP 200) */
    public const STATE_PAID = 'paid';
    /** @var string Payment realization failed (HTTP 200) */
    public const STATE_ERROR = 'error';
    /** @var string Payment is being processed asynchronously (HTTP 202) */
    public const STATE_WAITING_FOR_CONFIRMATION = 'waiting_for_confirmation';

    private string $state;

    private ?string $message;

    private bool $recurringPaymentsAvailable;

    /**
     * @param string|array<string, mixed> $data
     */
    public function __construct($data)
    {
        $decoded = is_array($data) ? $data : Json::decode($data, true);

        $this->state = $decoded['state'];
        $this->message = $decoded['message'] ?? null;
        $this->recurringPaymentsAvailable = $decoded['parent']['recurring_payments_available'] ?? true;
    }

    /**
     * @return self::STATE_*
     */
    public function getState(): string
    {
        return $this->state;
    }

    /**
     * Get optional message describing the result.
     */
    public function getMessage(): ?string
    {
        return $this->message;
    }

    /**
     * Check if more recurring payments can be realized using the parent payment.
     *
     * When false, the subscription/authorization should end because the parent
     * payment is no longer available for new realizations.
     *
     * In this case, you should inform the customer to create a new subscription
     * or authorize a new payment.
     */
    public function isRecurringPaymentsAvailable(): bool
    {
        return $this->recurringPaymentsAvailable;
    }
}
