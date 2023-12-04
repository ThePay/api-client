<?php

namespace ThePay\ApiClient\Tests;

use Mockery;
use ThePay\ApiClient\Http\HttpServiceInterface;
use ThePay\ApiClient\Model\RealizeIrregularSubscriptionPaymentParams;
use ThePay\ApiClient\Model\RealizeRegularSubscriptionPaymentParams;
use ThePay\ApiClient\Model\RealizeUsageBasedSubscriptionPaymentParams;
use ThePay\ApiClient\Tests\Mocks\Service\ApiMockService;
use ThePay\ApiClient\TheClient;

class RealizeSubscriptionPaymentTest extends BaseTestCase
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
    public function testRealizeSubscriptionPayment()
    {
        $params = new RealizeRegularSubscriptionPaymentParams('childPayment');
        $result = $this->client->realizeRegularSubscriptionPayment('parentUid', $params);

        static::assertTrue(get_class($result) === 'ThePay\ApiClient\Model\ApiResponse');

        $params = new RealizeIrregularSubscriptionPaymentParams('childPayment2');
        $result = $this->client->realizeIrregularSubscriptionPayment('parentUid', $params);

        static::assertTrue(get_class($result) === 'ThePay\ApiClient\Model\ApiResponse');

        $params = new RealizeUsageBasedSubscriptionPaymentParams('childPayment3', 1000);
        $result = $this->client->realizeUsageBasedSubscriptionPayment('parentUid', $params);

        static::assertTrue(get_class($result) === 'ThePay\ApiClient\Model\ApiResponse');
    }
}
