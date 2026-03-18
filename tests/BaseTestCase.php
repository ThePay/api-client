<?php

namespace ThePay\ApiClient\Tests;

use PHPUnit\Framework\TestCase;
use ThePay\ApiClient\Tests\Mocks\TheConfig;
use ThePay\ApiClient\TheClient;

abstract class BaseTestCase extends TestCase
{
    const MERCHANT_ID = '86a3eed0-95a4-11ea-ac9f-371f3488e0fa';

    /** @var TheConfig */
    protected $config;

    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $this->config = new TheConfig();
    }

    /**
     * method return TheClient witch use mock server
     *
     * @return TheClient
     */
    protected function getMockClient()
    {
        return new TheClient(new TheConfig());
    }
}
