<?php

declare(strict_types=1);

namespace ThePay\ApiClient\Tests;

use ThePay\ApiClient\Filter\TransactionFilter;
use ThePay\ApiClient\Http\HttpServiceInterface;
use ThePay\ApiClient\Tests\Mocks\Service\ApiMockService;
use ThePay\ApiClient\TheClient;

final class TransactionsTest extends BaseTestCase
{
    private TheClient $client;

    protected function setUp(): void
    {
        parent::setUp();

        $httpService = $this->createMock(HttpServiceInterface::class);
        $apiService = new ApiMockService($this->config, $httpService);

        $this->client = new TheClient($this->config, null, $httpService, $apiService);
    }

    public function testGettingTransactions(): void
    {
        $filter = new TransactionFilter('TP7811112150822790787055', new \DateTime('2021-01-01T12:30:00+00:00'), new \DateTime('2021-06-01T12:30:00+00:00'));
        $collection = $this->client->getAccountTransactionHistory($filter);

        self::assertSame(2, count($collection->all()));
    }

    public function testGettingTransactionsPaginatedCollection(): void
    {
        $filter = new TransactionFilter('TP7811112150822790787055', new \DateTime('2021-01-01T12:30:00+00:00'), new \DateTime('2021-06-01T12:30:00+00:00'));
        $collection = $this->client->getAccountTransactionHistory($filter);

        self::assertSame(2, $collection->getTotalCount());
    }
}
