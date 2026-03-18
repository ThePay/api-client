<?php

namespace ThePay\ApiClient\Tests;

use ThePay\ApiClient\TheClient;
use ThePay\ApiClient\ValueObject\Identifier;

class CancelPreauthorizationPaymentTest extends BaseTestCase
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
        $result = $this->client->cancelPreauthorizedPayment(new Identifier('abc'));
        self::assertTrue($result);
    }

    /**
     * @expectedException \Exception
     * @return void
     */
    public function testNotOkResponse()
    {
        $this->client->cancelPreauthorizedPayment(new Identifier(''));
    }
}
