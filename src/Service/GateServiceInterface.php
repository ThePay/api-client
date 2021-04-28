<?php

namespace ThePay\ApiClient\Service;

use ThePay\ApiClient\Model\Collection\PaymentMethodCollection;
use ThePay\ApiClient\Model\CreatePaymentParams;
use ThePay\ApiClient\TheConfig;

interface GateServiceInterface
{
    /**
     * @param TheConfig $config
     * @param ApiServiceInterface $api
     */
    public function __construct(TheConfig $config, ApiServiceInterface $api);

    public function getPaymentButtons(CreatePaymentParams $params, PaymentMethodCollection $methods);

    public function getPaymentButton($content, CreatePaymentParams $params, $methodCode = null, array $attributes = array(), $usePostMethod = true);

    public function getInlineAssets();

    public function getInlineStyles();

    public function getInlineScripts();
}
