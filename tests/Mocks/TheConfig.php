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
     * @param non-empty-string|null $specificVersion
     */
    public function getApiUrl(?string $specificVersion = null): string
    {
        if ($specificVersion !== null) {
            return 'http://openAPImock:1080/' . $specificVersion . '/';
        }
        return 'http://openAPImock:1080/v1/';
    }
}
