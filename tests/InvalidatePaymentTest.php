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

    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();
        $this->httpService = Mockery::mock('ThePay\ApiClient\Http\HttpServiceInterface');
        /** @phpstan-ignore-next-line */
        $apiService = new ApiService($this->config, $this->httpService);
        /** @phpstan-ignore-next-line */
        $this->client = new TheClient($this->config, null, $this->httpService, $apiService);
    }

    /**
     * @return void
     */
    public function testRequest()
    {
        call_user_func([$this->httpService, 'shouldReceive'], 'put')->once()
            ->with($this->config->getApiUrl() . 'projects/1/payments/abc/invalidate?merchant_id=' . self::MERCHANT_ID)
            ->andReturn($this->getOkResponse());

        $this->client->invalidatePayment('abc');
        \Mockery::close();
    }

    /**
     * @expectedException \Exception
     * @return void
     */
    public function testNotOkResponse()
    {
        call_user_func([$this->httpService, 'shouldReceive'], 'put')
            ->andReturn($this->getNotOkResponse());

        $this->client->invalidatePayment('abdc');
    }

    /**
     * @return HttpResponse
     */
    private function getOkResponse()
    {
        return new HttpResponse(null, 200);
    }

    /**
     * @return HttpResponse
     */
    private function getNotOkResponse()
    {
        return new HttpResponse(null, 404);
    }
}
