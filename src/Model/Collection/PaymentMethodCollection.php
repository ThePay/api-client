<?php

namespace ThePay\ApiClient\Model\Collection;

use ThePay\ApiClient\Filter\PaymentMethodFilter;
use ThePay\ApiClient\Model\PaymentMethod;
use ThePay\ApiClient\Utils\Json;
use ThePay\ApiClient\ValueObject\PaymentMethodTag;

/**
 * @extends Collection<PaymentMethod>
 */
class PaymentMethodCollection extends Collection
{
    /**
     * PaymentMethodCollection constructor.
     *
     * @param string|array<array<string, mixed>|PaymentMethod> $json Valid json string or decoded array
     */
    public function __construct($json)
    {
        $data = is_array($json) ? $json : Json::decode($json, true);

        foreach ($data as $d) {
            $this->add(is_object($d) ? $d : new PaymentMethod($d));
        }
    }

    /**
     * Get method by code.
     *
     * @param string $paymentMethodCode e.g. card or platba_24
     * @return PaymentMethod|null
     */
    public function get($paymentMethodCode)
    {
        /** @var PaymentMethod $method */
        foreach ($this->data as $method) {
            if ($method->getCode() === $paymentMethodCode) {
                return $method;
            }
        }

        return null;
    }

    /**
     * Filter payment methods by currencies or tags.
     *
     * @param PaymentMethodFilter $filter
     * @param bool $isRecurring
     * @param bool $isDeposit
     * @return PaymentMethodCollection
     */
    public function getFiltered(PaymentMethodFilter $filter, $isRecurring = false, $isDeposit = true)
    {
        $requiredCurrencies = $filter->getCurrencies();
        $mustHaveTags = $filter->getUsedTags();
        $canNotHaveTags = $filter->getBannedTags();

        if ($isRecurring) {
            $mustHaveTags[] = PaymentMethodTag::RECURRING_PAYMENTS;
        }
        if ( ! $isDeposit) {
            $mustHaveTags[] = PaymentMethodTag::PRE_AUTHORIZATION;
        }

        $requiredCurrencies = array_unique($requiredCurrencies);
        $mustHaveTags = array_unique($mustHaveTags);
        $canNotHaveTags = array_unique($canNotHaveTags);

        $result = $this->data;

        if ( ! empty($requiredCurrencies)) {
            $result = array_filter($result, function ($entry) use ($requiredCurrencies) {
                /** @var PaymentMethod $entry */
                $intersected = array_intersect($entry->getAvailableCurrencies(), $requiredCurrencies);
                // If the single payment method includes all required currencies, let it be else exclude it.
                return count($intersected) === count($requiredCurrencies);
            });
        }

        if ( ! empty($mustHaveTags)) {
            $result = array_filter($result, function ($entry) use ($mustHaveTags) {
                /** @var PaymentMethod $entry */
                $intersected = array_intersect($entry->getTags(), $mustHaveTags);
                // If the single payment method includes all required tags, let it be else exclude it.
                return count($intersected) === count($mustHaveTags);
            });
        }

        if ( ! empty($canNotHaveTags)) {
            $result = array_filter($result, function ($entry) use ($canNotHaveTags) {
                /** @var PaymentMethod $entry */
                $intersected = array_intersect($entry->getTags(), $canNotHaveTags);
                // If the single payment method includes on of the forbidden tags, exclude it else let it be.
                return empty($intersected);
            });
        }

        return new PaymentMethodCollection($result);
    }
}
