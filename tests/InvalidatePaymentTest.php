<?php

namespace ThePay\ApiClient\Tests;

use ThePay\ApiClient\TheClient;

class InvalidatePaymentTest extends BaseTestCase
{
    /** @var TheClient */
    private $client;

    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();
        $this->client = $this->getMockClient();
    }

    /**
     * @return void
     */
    public function testRequest()
    {
        $this->client->invalidatePayment('abc');

        self::assertTrue(true);
    }

    /**
     * @expectedException \Exception
     * @return void
     */
    public function testNotOkResponse()
    {
        $this->client->invalidatePayment('');
    }
}
