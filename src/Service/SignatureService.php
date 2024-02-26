<?php

namespace ThePay\ApiClient\Service;

use ThePay\ApiClient\Model\ApiSignature;
use ThePay\ApiClient\Model\CreatePaymentParams;
use ThePay\ApiClient\Model\SignedData;
use ThePay\ApiClient\TheConfig;

class SignatureService
{
    public const FORMAT_RFC7231 = 'D, d M Y H:i:s \G\M\T';

    private TheConfig $config;

    public function __construct(TheConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @throws \Exception
     */
    public function getSignatureForApi(): ApiSignature
    {
        $date = new \DateTime('now', new \DateTimeZone('GMT'));

        $date = $date->format(self::FORMAT_RFC7231);

        return new ApiSignature(
            $date,
            hash('sha256', $this->config->getMerchantId() . $this->config->getPassword() . $date)
        );
    }

    /**
     * @return SignedData
     */
    public function getSignedDataForGate(CreatePaymentParams $params)
    {
        $createPaymentData = $params->toArray();
        if (array_key_exists('customer', $createPaymentData) && is_array($createPaymentData['customer'])) {
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

    private function getSignatureForBase64(string $base64): string
    {
        return hash('sha256', $base64 . $this->config->getPassword());
    }

    /**
     * @param array<string, mixed> $data
     */
    private function getBase64FromParameters(array $data): string
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
}
