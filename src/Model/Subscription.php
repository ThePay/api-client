<?php

namespace ThePay\ApiClient\Model;

use Exception;
use ThePay\ApiClient\ValueObject\SubscriptionType;

class Subscription
{
    /** @var SubscriptionType */
    private $type;
    /** @var int|null */
    private $period;

    /**
     * @param string $type Subscription type, use values from SubscriptionType enum.
     * @param int|null $period required if type is regular and usagebased. Minimum days between payments realization, how often should be subscription paid.
     */
    public function __construct($type, $period = null)
    {
        if (($type === SubscriptionType::REGULAR || $type === SubscriptionType::USAGE_BASED) && $period === null) {
            throw new Exception('Period is required if subscription type is required ' . SubscriptionType::REGULAR . ' or ' . SubscriptionType::USAGE_BASED . '.');
        }
        $this->type = new SubscriptionType($type);
        $this->period = $period;
    }

    /**
     * @return SubscriptionType
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return int|null
     */
    public function getPeriod()
    {
        return $this->period;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray()
    {
        return array(
            'type' => (string) $this->type,
            'period' => $this->period,
        );
    }
}
