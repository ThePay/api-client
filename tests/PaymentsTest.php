<?php

declare(strict_types=1);

namespace ThePay\ApiClient\Tests;

use ThePay\ApiClient\Filter\PaymentsFilter;
use ThePay\ApiClient\Http\HttpServiceInterface;
use ThePay\ApiClient\Tests\Mocks\Service\ApiMockService;
use ThePay\ApiClient\TheClient;

final class PaymentsTest extends BaseTestCase
{
    private TheClient $client;

    protected function setUp(): void
    {
        parent::setUp();

        $httpService = $this->createMock(HttpServiceInterface::class);
        $apiService = new ApiMockService($this->config, $httpService);

        $this->client = new TheClient($this->config, null, $httpService, $apiService);
    }

    public function testGettingPayments(): void
    {
        $filter = new PaymentsFilter();
        $collection = $this->client->getPayments($filter);

        self::assertSame(2, count($collection->all()));
    }

    public function testGettingPaymentsPaginatedCollection(): void
    {
        $filter = new PaymentsFilter();
        $collection = $this->client->getPayments($filter);

        self::assertSame(2, $collection->getTotalCount());
    }

    public function testGetPayment(): void
    {
        $payment = $this->client->getPayment('test-UID');

        self::assertSame('efd7d8e6-2fa3-3c46-b475-51762331bf56', $payment->getUid());
    }

    public function testGetPaymentNullUid(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Payment UID cannot be null.');

        /** @phpstan-ignore-next-line */
        $this->client->getPayment(null);
    }

    public function testGetPaymentEmptyUid(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Payment UID cannot be empty string.');

        $this->client->getPayment('');
    }

    public function testGetPaymentUidNotStringable(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Payment UID cannot be converted to string.');

        /** @phpstan-ignore-next-line */
        $this->client->getPayment(new \stdClass());
    }
}
