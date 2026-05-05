<?php

namespace ThePay\ApiClient\Tests\Mocks;

use ThePay\ApiClient\Tests\BaseTestCase;

class TheConfig extends \ThePay\ApiClient\TheConfig
{
    public function __construct()
    {
        parent::__construct(
            BaseTestCase::MERCHANT_ID,
            1,
            'password',
            'https://secure-url/',
            'https://private-ddc40-gatezalozeniplatby.apiary-mock.com/'
        );
    }

    /**
     * @return string
     */
    public function getApiUrl()
    {
        return 'http://localhost:1080/v1/';
    }
}
