<?php

namespace ThePay\ApiClient\Service;

use ThePay\ApiClient\Model\Collection\PaymentMethodCollection;
use ThePay\ApiClient\Model\CreatePaymentParams;
use ThePay\ApiClient\TheConfig;
use ThePay\ApiClient\ValueObject\Identifier;
use ThePay\ApiClient\ValueObject\LanguageCode;

interface GateServiceInterface
{
    /**
     * @param TheConfig $config
     * @param ApiServiceInterface $api
     */
    public function __construct(TheConfig $config, ApiServiceInterface $api);

    /**
     * @return string HTML
     */
    public function getPaymentButtons(CreatePaymentParams $params, PaymentMethodCollection $methods);

    /**
     * @param Identifier $uid UID of payment
     * @return string HTML
     */
    public function getPaymentButtonsForPayment(Identifier $uid, LanguageCode $languageCode = null);

    /**
     * @param string $content HTML content of button
     * @param string|null $methodCode
     * @param array<string, string> $attributes
     * @param bool $usePostMethod
     * @return string HTML
     */
    public function getPaymentButton($content, CreatePaymentParams $params, $methodCode = null, array $attributes = [], $usePostMethod = true);

    /**
     * @return string HTML
     */
    public function getInlineAssets();

    /**
     * @return string HTML
     */
    public function getInlineStyles();

    /**
     * @return string HTML
     */
    public function getInlineScripts();
}
