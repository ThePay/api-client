<?php

namespace ThePay\ApiClient\Http;

class CurlWrapperFactory
{
    /**
     * @param array<string> $defaultHeaders
     * @return CurlWrapper
     */
    public function create(array $defaultHeaders)
    {
        $curl = curl_init();
        return new CurlWrapper($curl, $defaultHeaders);
    }
}
