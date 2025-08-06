<?php

namespace ThePay\ApiClient\Service;

use ThePay\ApiClient\Model\ApiSignature;
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
}
