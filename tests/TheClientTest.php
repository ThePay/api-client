<?php

namespace ThePay\ApiClient\Tests;

use ThePay\ApiClient\Model\Collection\PaymentMethodCollection;
use ThePay\ApiClient\Service\ApiServiceInterface;
use ThePay\ApiClient\TheClient;

class TheClientTest extends BaseTestCase
{
    public function testPaymentMethods(): void
    {
        $apiService = $this->createMock(ApiServiceInterface::class);
        $apiService->method('getActivePaymentMethods')->willReturn(
            new PaymentMethodCollection([
                [
                    'code' => 'test_online',
                    'title' => 'shared::payment_methods.test_online',
                    'tags' => ['access_account_owner', 'online', 'returnable'],
                    'available_currencies' => [
                        [
                            'code' => 'CZK',
                            'numeric_code' => '203',
                        ],
                    ],
                    'image' => [
                        'src' => 'http://localhost:8000/img/payment_methods/test_online.png',
                    ],
                ],
                [
                    'code' => 'test_offline',
                    'title' => 'shared::payment_methods.test_offline',
                    'tags' => ['access_account_owner', 'returnable'],
                    'available_currencies' => [
                        [
                            'code' => 'CZK',
                            'numeric_code' => '203',
                        ],
                    ],
                    'image' => [
                        'src' => 'http://localhost:8000/img/payment_methods/test_offline.png',
                    ],
                ],
            ])
        );

        $thePay = new TheClient($this->config, $apiService);

        $methods = $thePay->getActivePaymentMethods()->all();

        static::assertSame('test_online', $methods[0]->getCode());
        static::assertSame('CZK', $methods[1]->getAvailableCurrencies()[0]);
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
}
