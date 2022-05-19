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

    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        /** @var HttpServiceInterface $httpService */
        $httpService = Mockery::mock('ThePay\ApiClient\Http\HttpServiceInterface');
        $apiService = new ApiMockService($this->config, $httpService);

        $this->client = new TheClient($this->config, null, $httpService, $apiService);
    }

    /**
     * @return void
     */
    public function testGettingPayments()
    {
        $filter = new PaymentsFilter();
        $collection = $this->client->getPayments($filter);

        static::assertSame(2, count($collection->all()));
    }

    /**
     * @return void
     */
    public function testGettingPaymentsPaginatedCollection()
    {
        $filter = new PaymentsFilter();
        $collection = $this->client->getPayments($filter);

        static::assertSame(2, $collection->getTotalCount());
    }

    /**
     * @return void
     */
    public function testGetPayment()
    {
        $payment = $this->client->getPayment('test-UID');

        static::assertSame('efd7d8e6-2fa3-3c46-b475-51762331bf56', $payment->getUid());
    }

    /**
     * @return void
     */
    public function testGetPaymentNullUid()
    {
        static::setExpectedException('InvalidArgumentException', 'Payment UID cannot be null.');
        /** @phpstan-ignore-next-line */
        $this->client->getPayment(null);
    }

    /**
     * @return void
     */
    public function testGetPaymentEmptyUid()
    {
        static::setExpectedException('InvalidArgumentException', 'Payment UID cannot be empty string.');
        $this->client->getPayment('');
    }

    /**
     * @return void
     */
    public function testGetPaymentUidNotStringable()
    {
        static::setExpectedException('InvalidArgumentException', 'Payment UID cannot be converted to string.');
        /** @phpstan-ignore-next-line */
        $this->client->getPayment(new \stdClass());
    }
}
