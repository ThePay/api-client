<?php

declare(strict_types=1);

namespace ThePay\ApiClient\Tests;

use ThePay\ApiClient\TheClient;

final class CancelPreauthorizationPaymentTest extends BaseTestCase
{
    private TheClient $client;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = $this->getMockClient();
    }

    public function testRequest(): void
    {
        $this->client->cancelPreauthorizedPayment('abc');
        self::assertTrue(true);
    }

    public function testNotOkResponse(): void
    {
        $this->expectException(\Exception::class);

        /** @phpstan-ignore-next-line */
        $this->client->cancelPreauthorizedPayment('');
    }
}
