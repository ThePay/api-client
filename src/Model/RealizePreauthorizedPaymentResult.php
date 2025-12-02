<?php

namespace ThePay\ApiClient\Model;

use ThePay\ApiClient\Utils\Json;

/**
 * Result of preauthorized payment realization.
 *
 * @see https://docs.thepay.eu/#tag/Preauthorized-Payments/paths/~1v2~1projects~1{project_id}~1payments~1{payment_uid}~1preauthorized/post
 */
class RealizePreauthorizedPaymentResult
{
    /** @var string Payment was realized successfully (HTTP 200) */
    public const STATE_PAID = 'paid';
    /** @var string Payment is being processed asynchronously (HTTP 202) */
    public const STATE_WAITING_FOR_CONFIRMATION = 'waiting_for_confirmation';

    private string $state;

    /**
     * @param string|array<string, mixed> $data
     */
    public function __construct($data)
    {
        $decoded = is_array($data) ? $data : Json::decode($data, true);

        $this->state = $decoded['state'];
    }

    /**
     * @return self::STATE_*
     */
    public function getState(): string
    {
        return $this->state;
    }
}
