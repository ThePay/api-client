<?php

namespace ThePay\ApiClient;

use ThePay\ApiClient\ValueObject\LanguageCode;
use ThePay\ApiClient\ValueObject\SecureUrl;

class TheConfig
{
    /** @var string */
    private $merchantId;

    /** @var int */
    private $projectId;

    /** @var string */
    private $password;

    /** @var SecureUrl */
    private $apiUrl;

    /** @var SecureUrl */
    private $gateUrl;

    /** @var string */
    private $apiVersion = 'v1';

    /** @var LanguageCode Language for API responses */
    private $language;

    /**
     * TheConfig constructor.
     *
     * @param string $merchantId - the identifier of merchant
     * @param int $projectId - the identifier of project, merchant may have a multiple projects
     * @param string $apiPassword - password for API, should not be the same as the password for logging into administration
     * @param string $apiUrl - base url for all API calls
     * @param string $gateUrl - gate application base url for user frontend
     * @param string $language - You may override this parameter later in request parameters, but this one will be used as a default value. The component requires format ISO 639‑1. Default value is cs.
     */
    public function __construct($merchantId, $projectId, $apiPassword, $apiUrl, $gateUrl, $language = 'cs')
    {
        $this->merchantId = (string) $merchantId;
        $this->projectId = (int) $projectId;
        $this->password = (string) $apiPassword;
        $this->apiUrl = new SecureUrl($apiUrl);
        $this->gateUrl = new SecureUrl($gateUrl);
        $this->language = new LanguageCode($language);
    }

    /**
     * @return string
     */
    public function getGateUrl()
    {
        return $this->gateUrl->getValue();
    }

    /**
     * @return string
     */
    public function getApiUrl()
    {
        return $this->apiUrl->getValue() . $this->apiVersion . '/';
    }

    /**
     * @return string
     */
    public function getMerchantId()
    {
        return $this->merchantId;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @return int
     */
    public function getProjectId()
    {
        return $this->projectId;
    }

    /**
     * @return LanguageCode
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * @param string $language ISO 6391 format
     * @return void
     */
    public function setLanguage($language)
    {
        $this->language = new LanguageCode($language);
    }
}
