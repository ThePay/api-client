<?php

namespace ThePay\ApiClient\Service;

use ThePay\ApiClient\Model\ApiSignature;
use ThePay\ApiClient\Model\CreatePaymentParams;
use ThePay\ApiClient\Model\SignedData;
use ThePay\ApiClient\TheConfig;

final class SignatureService
{
    const FORMAT_RFC7231 = 'D, d M Y H:i:s \G\M\T';

    /** @var TheConfig */
    private $config;

    /**
     * SignatureService constructor.
     *
     * @param TheConfig $config
     */
    public function __construct(TheConfig $config)
    {
        $this->config = $config;
    }

    public function getRequestHeaders()
    {
    }

    /**
     * @param string $query
     * @return string
     */
    public function signGateRequest($query)
    {
        $query = 'merchant_id=' . $this->config->getMerchantId() . $query;
        return $this->getSignatureHashForQuery($query);
    }

    /**
     * @return ApiSignature
     * @throws \Exception
     */
    public function getSignatureForApi()
    {
        $date = new \DateTime();

        $date = $date->format(self::FORMAT_RFC7231);

        return new ApiSignature(
            $date,
            hash('sha256', $this->config->getMerchantId().$this->config->getPassword().$date)
        );
    }

    /**
     * @return string
     */
    public function getBase64FromParameters(array $data)
    {
        $toEncode = $data;

        $toEncode['merchant_id'] = $this->config->getMerchantId();
        $toEncode['project_id'] = $this->config->getProjectId();

        if (empty($toEncode['language_code'])) {
            $toEncode['language_code'] = $this->config->getLanguage();
        }

        $toEncode = array_filter($toEncode, function ($entry) {
            return $entry !== null;
        });

        return base64_encode(json_encode($toEncode));
    }

    /**
     * @param string $base64
     *
     * @return string
     */
    public function getSignatureForBase64($base64)
    {
        return hash('sha256', $base64 . $this->config->getPassword());
    }

    /**
     * @return SignedData
     */
    public function getSignedDataForGate(CreatePaymentParams $params)
    {
        $createPaymentData = $params->toArray();
        if (array_key_exists('customer', $createPaymentData)) {
            $createPaymentData['customer_name'] = $createPaymentData['customer']['name'];
            $createPaymentData['customer_surname'] = $createPaymentData['customer']['surname'];
            $createPaymentData['customer_email'] = $createPaymentData['customer']['email'];
            $createPaymentData['customer_phone'] = $createPaymentData['customer']['phone'];

            if (array_key_exists('billing_address', $createPaymentData['customer'])) {
                $createPaymentData['customer_billing_country_code'] = $createPaymentData['customer']['billing_address']['country_code'];
                $createPaymentData['customer_billing_city'] = $createPaymentData['customer']['billing_address']['city'];
                $createPaymentData['customer_billing_zip'] = $createPaymentData['customer']['billing_address']['zip'];
                $createPaymentData['customer_billing_street'] = $createPaymentData['customer']['billing_address']['street'];
            }

            unset($createPaymentData['customer']);
        }

        $base64 = $this->getBase64FromParameters($createPaymentData);

        return new SignedData(
            $base64,
            $this->getSignatureForBase64($base64)
        );
    }

    /**
     * @param array $parameters
     * @return string
     */
    private function getSignatureHash(array $parameters)
    {
        $query = $this->getStringFromParameters($parameters);

        return $this->getSignatureHashForQuery($query);
    }

    /**
     * @param string $query
     * @return string
     */
    private function getSignatureHashForQuery($query)
    {
        $query .= '&password=' . $this->config->getPassword();

        return hash('sha256', $query);
    }

    /**
     * @param array $parameters
     * @return string
     */
    private function getStringFromParameters(array $parameters)
    {
        $result = '?';

        foreach ($parameters as $k => $v) {
            $result .= $k . '=' . $this->sanitizeValues($v);
        }

        return $result;
    }

    /**
     * @param string[]|string $value
     * @return string
     */
    private function sanitizeValues($value)
    {
        if (is_array($value)) {
            return implode('|', $value);
        }

        // todo: objects

        return $value;
    }

    /**
     * @param array $parameters
     * @return string
     */
    private function getUrlParams(array $parameters)
    {
        $parameters['merchant_id'] = $this->config->getMerchantId();
        $parameters['signature'] = $this->getSignatureHash($parameters);

        return http_build_query($parameters, null, '&');
    }
}
