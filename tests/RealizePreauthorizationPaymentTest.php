<?php

namespace ThePay\ApiClient\Tests;

use ThePay\ApiClient\Model\RealizePreauthorizedPaymentParams;
use ThePay\ApiClient\TheClient;

class RealizePreauthorizationPaymentTest extends BaseTestCase
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
        $result = $this->client->realizePreauthorizedPayment(new RealizePreauthorizedPaymentParams(9945, 'efd7d8e6-2fa3-3c46-b475-51762331bf56'));

        self::assertTrue($result);
    }

    /**
     * @return void
     */
    public function testNotOkResponse()
    {
        $this->setExpectedException('\Exception');

        $this->client->realizePreauthorizedPayment(new RealizePreauthorizedPaymentParams(100, ''));
    }
}
