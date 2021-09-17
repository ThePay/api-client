<?php

namespace ThePay\ApiClient\Tests;

use Mockery;
use ThePay\ApiClient\Http\HttpResponse;
use ThePay\ApiClient\Service\ApiService;
use ThePay\ApiClient\TheClient;

class InvalidatePaymentTest extends BaseTestCase
{
    /** @var \Mockery\LegacyMockInterface|\ThePay\ApiClient\Http\HttpServiceInterface */
    private $httpService;

    /** @var TheClient */
    private $client;

    protected function setUp()
    {
        parent::setUp();
        $this->httpService = Mockery::mock('ThePay\ApiClient\Http\HttpServiceInterface');
        $apiService = new ApiService($this->config, $this->httpService);
        $this->client = new TheClient($this->config, null, $this->httpService, $apiService);
    }

    public function testRequest()
    {
        call_user_func(array($this->httpService, 'shouldReceive'), 'put')->once()
            ->with($this->config->getApiUrl() . 'projects/1/payments/abc/invalidate?merchant_id=' . self::MERCHANT_ID)
            ->andReturn($this->getOkResponse());

        $this->client->invalidatePayment('abc');
        \Mockery::close();
    }

    /**
     * @expectedException \Exception
     * @throws \Exception
     */
    public function testNotOkResponse()
    {
        call_user_func(array($this->httpService, 'shouldReceive'), 'put')
            ->andReturn($this->getNotOkResponse());

        $this->client->invalidatePayment('abdc');
    }

    private function getOkResponse()
    {
        return new HttpResponse(null, 200);
    }

    private function getNotOkResponse()
    {
        return new HttpResponse(null, 404);
    }
}
