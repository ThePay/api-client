<?php

namespace ThePay\ApiClient\Filter;

class PaymentMethodFilter
{
    /** @var string[] */
    private $currencies;

    /** @var string[] */
    private $usedTags;

    /** @var string[] */
    private $bannedTags;

    /**
     * PaymentMethodFilter constructor.
     *
     * @param string[]  $currencies
     * @param string[]  $usedTags
     * @param string[]  $bannedTags
     */
    public function __construct(array $currencies, array $usedTags, array $bannedTags)
    {
        $this->currencies = $currencies;
        $this->usedTags = $usedTags;
        $this->bannedTags = $bannedTags;
    }

    /**
     * @return string[]
     */
    public function getCurrencies()
    {
        return $this->currencies;
    }

    /**
     * @return string[]
     */
    public function getUsedTags()
    {
        return $this->usedTags;
    }

    /**
     * @return string[]
     */
    public function getBannedTags()
    {
        return $this->bannedTags;
    }

    /**
     * @param string $currencyCode
     */
    public function setCurrency($currencyCode)
    {
        $this->currencies = array($currencyCode);
    }
}
