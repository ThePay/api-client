<?php

declare(strict_types=1);

namespace ThePay\ApiClient\Tests;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\HttpFactory;
use PHPUnit\Framework\TestCase;
use ThePay\ApiClient\Service\ApiService;
use ThePay\ApiClient\Service\SignatureService;
use ThePay\ApiClient\Tests\Mocks\TheConfig;
use ThePay\ApiClient\TheClient;

abstract class BaseTestCase extends TestCase
{
    public const MERCHANT_ID = '86a3eed0-95a4-11ea-ac9f-371f3488e0fa';

    protected TheConfig $config;

    protected function setUp(): void
    {
        parent::setUp();

        $this->config = new TheConfig();
    }

    /**
     * method return TheClient witch use mock server
     */
    protected function getMockClient(): TheClient
    {
        $config = new TheConfig();

        $httpFactory = new HttpFactory();

        return new TheClient(
            $config,
            new ApiService(
                $config,
                new SignatureService($config),
                new Client(),
                $httpFactory,
                $httpFactory
            )
        );
    }
}
