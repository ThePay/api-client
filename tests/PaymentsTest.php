<?php

namespace ThePay\ApiClient\Tests;

use Mockery;
use ThePay\ApiClient\Filter\PaymentsFilter;
use ThePay\ApiClient\Http\HttpServiceInterface;
use ThePay\ApiClient\Tests\Mocks\Service\ApiMockService;
use ThePay\ApiClient\TheClient;

class PaymentsTest extends BaseTestCase
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

    public function testGettingPayments()
    {
        $filter = new PaymentsFilter();
        $collection = $this->client->getPayments($filter);

        static::assertSame(2, count($collection->all()));
    }

    public function testGettingPaymentsPaginatedCollection()
    {
        $filter = new PaymentsFilter();
        $collection = $this->client->getPayments($filter);

        static::assertSame(2, $collection->getTotalCount());
    }
}
