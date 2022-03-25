<?php

namespace ThePay\ApiClient\Tests;

use Mockery;
use ThePay\ApiClient\Http\HttpServiceInterface;
use ThePay\ApiClient\Model\RealizePaymentBySavedAuthorizationParams;
use ThePay\ApiClient\Tests\Mocks\Service\ApiMockService;
use ThePay\ApiClient\TheClient;

class RealizeSavedAuthorizationPaymentTest extends BaseTestCase
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
    public function testRealizePaymentBySavedAuthorization()
    {
        $params = new RealizePaymentBySavedAuthorizationParams('childPayment');
        $result = $this->client->realizePaymentBySavedAuthorization('parentUid', $params);

        static::assertTrue(get_class($result) === 'ThePay\ApiClient\Model\ApiResponse');
    }
}
