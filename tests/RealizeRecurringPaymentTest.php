<?php

namespace ThePay\ApiClient\Tests;

use Mockery;
use ThePay\ApiClient\Http\HttpResponse;
use ThePay\ApiClient\Model\CreateRecurringPaymentParams;
use ThePay\ApiClient\Service\ApiService;
use ThePay\ApiClient\TheClient;

class RealizeRecurringPaymentTest extends BaseTestCase
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

    /**
     * @throws \Exception
     */
    public function testOkResponse()
    {
        call_user_func(array($this->httpService, 'shouldReceive'), 'post')
            ->andReturn($this->getOkResponse());

        $params = new CreateRecurringPaymentParams('success', 'def', 1, 1, 'CZK');
        $response = $this->client->realizeRecurringPayment($params);
        static::assertEquals('success', $response->getState());
    }

    /**
     * @throws \Exception
     */
    public function testOkFailedResponse()
    {
        call_user_func(array($this->httpService, 'shouldReceive'), 'post')
            ->andReturn($this->getOkFailedResponse());

        $params = new CreateRecurringPaymentParams('failed', 'def', 1, 1, 'CZK');
        $response = $this->client->realizeRecurringPayment($params);
        static::assertEquals('failed', $response->getState());
    }

    /**
     * @expectedException \Exception
     * @throws \Exception
     */
    public function testNotOkResponse()
    {
        call_user_func(array($this->httpService, 'shouldReceive'), 'post')
            ->andReturn($this->getNotOkResponse());

        $params = new CreateRecurringPaymentParams('abc', 'def', 1, 1, 'CZK');
        $this->client->realizeRecurringPayment($params);
    }

    private function getOkResponse()
    {
        return new HttpResponse(null, 200, '', null, '{"state": "success","message": "message"}');
    }

    private function getOkFailedResponse()
    {
        return new HttpResponse(null, 200, '', null, '{"state": "failed","message": "message"}');
    }

    private function getNotOkResponse()
    {
        return new HttpResponse(null, 401, '', null, '{"message": "message"}');
    }
}
