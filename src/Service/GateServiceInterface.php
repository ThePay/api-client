<?php

namespace ThePay\ApiClient\Service;

use ThePay\ApiClient\ValueObject\Identifier;
use ThePay\ApiClient\ValueObject\LanguageCode;

interface GateServiceInterface
{
    /**
     * @param ApiServiceInterface $api
     */
    public function __construct(ApiServiceInterface $api);

    /**
     * @param Identifier $uid UID of payment
     * @return string HTML
     */
    public function getPaymentButtonsForPayment(Identifier $uid, ?LanguageCode $languageCode = null);

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
