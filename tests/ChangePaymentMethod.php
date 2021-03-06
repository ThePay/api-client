<?php

namespace ThePay\ApiClient\Tests;

use Mockery;
use ThePay\ApiClient\Http\HttpResponse;
use ThePay\ApiClient\Service\ApiService;
use ThePay\ApiClient\TheClient;
use ThePay\ApiClient\ValueObject\PaymentMethodCode;

class ChangePaymentMethod extends BaseTestCase
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
        /** @phpstan-ignore-next-line  */
        $apiService = new ApiService($this->config, $this->httpService);
        /** @phpstan-ignore-next-line  */
        $this->client = new TheClient($this->config, null, $this->httpService, $apiService);
    }

    /**
     * @return void
     */
    public function testRequest()
    {
        call_user_func(array($this->httpService, 'shouldReceive'), 'put')->once()
            ->with($this->config->getApiUrl() . 'projects/1/payments/abc/method?merchant_id=' . self::MERCHANT_ID, json_encode(
                array(
                    'payment_method_code' => new PaymentMethodCode(PaymentMethodCode::TRANSFER),
                )
            ))
            ->andReturn($this->getOkResponse());

        $this->client->changePaymentMethod('abc', new PaymentMethodCode(PaymentMethodCode::TRANSFER));
        \Mockery::close();
    }

    /**
     * @expectedException \Exception
     * @return void
     */
    public function testNotOkResponse()
    {
        call_user_func(array($this->httpService, 'shouldReceive'), 'delete')
            ->andReturn($this->getNotOkResponse());

        $this->client->changePaymentMethod('abc', new PaymentMethodCode(PaymentMethodCode::TRANSFER));
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
        return new HttpResponse(null, 404);
    }
}
