<?php

declare(strict_types=1);

namespace ThePay\ApiClient\Tests;

use PHPUnit\Framework\TestCase;
use ThePay\ApiClient\TheClient;
use ThePay\ApiClient\TheConfig;

abstract class BaseTestCase extends TestCase
{
    public const MERCHANT_ID = '86a3eed0-95a4-11ea-ac9f-371f3488e0fa';

    protected TheConfig $config;

    protected function setUp(): void
    {
        parent::setUp();

        $this->config = new TheConfig(self::MERCHANT_ID, 1, 'password', 'https://test.api.cz/', 'https://test.gate.cz/');
    }

    /**
     * method return TheClient witch use apiary mock server
     */
    protected function getApiaryClient(): TheClient
    {
        $config = new TheConfig(
            '6cdf1b24',
            1212,
            'password',
            'https://private-472c9-dataapi21.apiary-mock.com/',
            'https://private-ddc40-gatezalozeniplatby.apiary-mock.com/'
        );
        return new TheClient($config);
    }
}
