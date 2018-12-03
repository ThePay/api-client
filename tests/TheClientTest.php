<?php

namespace ThePay\ApiClient\Tests;

use Mockery;
use ThePay\ApiClient\Tests\Mocks\Service\ApiMockService;
use ThePay\ApiClient\TheClient;

/**
 * @deprecated Remove when tests will be splitted
 */
class TheClientTest extends BaseTestCase
{
    public function testPaymentMethods()
    {
        $thePay = $this->getExampleTheClient();

        $methods = $thePay->getActivePaymentMethods()->all();

        static::assertSame('test_online', $methods[0]->getCode());

        $assert2 = $methods[1]->getAvailableCurrencies();
        static::assertSame('CZK', $assert2[0]);
    }

    public function testRenderPaymentMethods()
    {
        /*
        $thePay = $this->getExampleTheClient();

        $html = $thePay->renderPaymentMethods(new CreatePaymentParams('10', 'CZK', 'xxxxxx'));
        static::assertContains('img', 'test');
        */
    }

    private function getExampleTheClient()
    {
        $httpService = Mockery::mock('ThePay\ApiClient\Http\HttpServiceInterface');
        $apiService = new ApiMockService($this->config, $httpService);

        return new TheClient($this->config, null, $httpService, $apiService);
    }
}
