<?php

namespace ThePay\ApiClient\Tests\Mocks;

class TheConfig extends \ThePay\ApiClient\TheConfig
{
    public function __construct()
    {
        parent::__construct(
            'a471eab0-4054-11ef-ac09-116afd5362fb',
            1212,
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
        return 'http://openAPImock:1080/v1/';
    }
}
