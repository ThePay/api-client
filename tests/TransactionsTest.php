<?php

namespace ThePay\ApiClient\Tests;

use Mockery;
use ThePay\ApiClient\Filter\TransactionFilter;
use ThePay\ApiClient\Http\HttpServiceInterface;
use ThePay\ApiClient\Tests\Mocks\Service\ApiMockService;
use ThePay\ApiClient\TheClient;

class TransactionsTest extends BaseTestCase
{
    /** @var TheClient */
    private $client;

    protected function setUp()
    {
        parent::setUp();

        /** @var HttpServiceInterface $httpService */
        $httpService = Mockery::mock('ThePay\ApiClient\Http\HttpServiceInterface');
        $apiService = new ApiMockService($this->config, $httpService);

        $this->client = new TheClient($this->config, null, $httpService, $apiService);
    }

    public function testGettingTransactions()
    {
        $filter = new TransactionFilter('TP7811112150822790787055', new \DateTime('2021-01-01T12:30:00+00:00'), new \DateTime('2021-06-01T12:30:00+00:00'));
        $collection = $this->client->getAccountTransactionHistory($filter);

        static::assertSame(2, count($collection->all()));
    }

    public function testGettingTransactionsPaginatedCollection()
    {
        $filter = new TransactionFilter('TP7811112150822790787055', new \DateTime('2021-01-01T12:30:00+00:00'), new \DateTime('2021-06-01T12:30:00+00:00'));
        $collection = $this->client->getAccountTransactionHistory($filter);

        static::assertSame(2, $collection->getTotalCount());
    }
}
