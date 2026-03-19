<?php

declare(strict_types=1);

namespace ThePay\ApiClient\Tests;

use ThePay\ApiClient\Model\RealizePreauthorizedPaymentParams;
use ThePay\ApiClient\TheClient;

final class RealizePreauthorizationPaymentTest extends BaseTestCase
{
    private TheClient $client;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->client = $this->getMockClient();
    }

    public function testRequest(): void
    {
        $this->client->realizePreauthorizedPayment(new RealizePreauthorizedPaymentParams(9945, 'efd7d8e6-2fa3-3c46-b475-51762331bf56'));

        self::assertTrue(true);
    }

    public function testNotOkResponse(): void
    {
        $this->expectException(\Exception::class);

        $this->client->realizePreauthorizedPayment(new RealizePreauthorizedPaymentParams(100, ''));
    }
}
