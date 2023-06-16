<?php

namespace ThePay\ApiClient\Tests;

use Mockery;
use ThePay\ApiClient\Http\HttpResponse;
use ThePay\ApiClient\Model\RealizePreauthorizedPaymentParams;
use ThePay\ApiClient\Service\ApiService;
use ThePay\ApiClient\TheClient;

class RealizePreauthorizationPaymentTest extends BaseTestCase
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
        call_user_func([$this->httpService, 'shouldReceive'], 'post')->once()
            ->with($this->config->getApiUrl() . 'projects/1/payments/abc/preauthorized?merchant_id=' . self::MERCHANT_ID, '{"amount":100}')
            ->andReturn($this->getOkResponse());

        $this->client->realizePreauthorizedPayment(new RealizePreauthorizedPaymentParams(100, 'abc'));
        \Mockery::close();
    }

    /**
     * @return void
     */
    public function testNotOkResponse()
    {
        $this->setExpectedException('\Exception');

        call_user_func([$this->httpService, 'shouldReceive'], 'post')
            ->andReturn($this->getNotOkResponse());

        $this->client->realizePreauthorizedPayment(new RealizePreauthorizedPaymentParams(100, 'abc'));
    }

    /**
     * @return HttpResponse
     */
    private function getOkResponse()
    {
        return new HttpResponse(null, 204);
    }

    /**
     * @return HttpResponse
     */
    private function getNotOkResponse()
    {
        return new HttpResponse(null, 401);
    }
}
