<?php

namespace ThePay\ApiClient\Tests;

use Mockery;
use ThePay\ApiClient\Http\HttpResponse;
use ThePay\ApiClient\Model\RealizePreauthorizedPaymentParams;
use ThePay\ApiClient\Service\ApiService;
use ThePay\ApiClient\TheClient;

class RealizePreauthorizationPaymentTest extends BaseTestCase
{
    /** @var \ThePay\ApiClient\Http\HttpServiceInterface|Mockery\MockInterface */
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
        call_user_func(array($this->httpService, 'shouldReceive'), 'post')->once()
            ->with($this->config->getApiUrl() . 'projects/1/payments/abc/preauthorized?merchant_id=' . self::MERCHANT_ID, '{"amount":100}')
            ->andReturn($this->getOkResponse());

        $this->client->realizePreauthorizedPayment(new RealizePreauthorizedPaymentParams(100, 'abc'));
        \Mockery::close();
    }

    /**
     * @throws \Exception
     */
    public function testNotOkResponse()
    {
        $this->setExpectedException('\Exception');

        call_user_func(array($this->httpService, 'shouldReceive'), 'post')
            ->andReturn($this->getNotOkResponse());

        $this->client->realizePreauthorizedPayment(new RealizePreauthorizedPaymentParams(100, 'abc'));
    }

    private function getOkResponse()
    {
        return new HttpResponse(null, 204);
    }

    private function getNotOkResponse()
    {
        return new HttpResponse(null, 401);
    }
}
