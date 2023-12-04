<?php

namespace ThePay\ApiClient\Http;

/**
 * @deprecated will be replaced by https://www.php-fig.org/psr/psr-18/ interface
 */
class CurlWrapperFactory
{
    /**
     * @deprecated will be replaced by https://www.php-fig.org/psr/psr-18/ interface
     *
     * @param array<string> $defaultHeaders
     * @return CurlWrapper
     */
    public function create(array $defaultHeaders)
    {
        $curl = curl_init();
        return new CurlWrapper($curl, $defaultHeaders);
    }
}
