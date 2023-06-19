<?php

namespace ThePay\ApiClient\Tests;

use ThePay\ApiClient\Http\HttpServiceInterface;
use ThePay\ApiClient\Tests\Mocks\Service\ApiMockService;
use ThePay\ApiClient\TheClient;

/**
 * @deprecated Remove when tests will be splitted
 */
class TheClientTest extends BaseTestCase
{
    /**
     * @return void
     */
    public function testPaymentMethods()
    {
        $thePay = $this->getExampleTheClient();

        $methods = $thePay->getActivePaymentMethods()->all();

        static::assertSame('test_online', $methods[0]->getCode());

        $assert2 = $methods[1]->getAvailableCurrencies();
        static::assertSame('CZK', $assert2[0]);
    }

    public function testRenderPaymentMethods(): void
    {
        self::markTestSkipped();

        /*
        $thePay = $this->getExampleTheClient();

        $html = $thePay->renderPaymentMethods(new CreatePaymentParams('10', 'CZK', 'xxxxxx'));
        static::assertContains('img', 'test');
        */
    }

    private function getExampleTheClient(): TheClient
    {
        $httpService = $this->createMock(HttpServiceInterface::class);
        $apiService = new ApiMockService($this->config, $httpService);

        return new TheClient($this->config, null, $httpService, $apiService);
    }
}
