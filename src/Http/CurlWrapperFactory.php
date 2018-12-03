<?php

namespace ThePay\ApiClient\Http;

class CurlWrapperFactory
{
    public function create(array $defaultHeaders)
    {
        $curl = curl_init();
        return new CurlWrapper($curl, $defaultHeaders);
    }
}
