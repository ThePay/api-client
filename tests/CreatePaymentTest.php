<?php

declare(strict_types=1);

namespace ThePay\ApiClient\Tests;

use PHPUnit\Framework\MockObject\MockObject;
use ThePay\ApiClient\Model\Address;
use ThePay\ApiClient\Model\Collection\PaymentMethodCollection;
use ThePay\ApiClient\Model\CreatePaymentCustomer;
use ThePay\ApiClient\Model\CreatePaymentParams;
use ThePay\ApiClient\Model\CreatePaymentResponse;
use ThePay\ApiClient\Model\PaymentMethod;
use ThePay\ApiClient\Service\ApiServiceInterface;
use ThePay\ApiClient\TheClient;

final class CreatePaymentTest extends BaseTestCase
{
    private TheClient $client;

    private MockObject $apiService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->apiService = $this->createMock(ApiServiceInterface::class);
        $this->client = new TheClient($this->config, $this->apiService);
    }


    /**
     * @dataProvider createButtonProvider
     */
    public function testCreateButton(CreatePaymentParams $params, string $data, string $signature): void
    {
        $r = $this->client->getPaymentButton($params);

        self::assertStringContainsString($data, $r);
        self::assertStringContainsString($signature, $r);
    }

    /**
     * @return array<array<mixed>>
     */
    public static function createButtonProvider(): array
    {
        return [
            [
                new CreatePaymentParams(100, 'CZK', '202001010001'),
                'eyJhbW91bnQiOjEwMCwiY3VycmVuY3lfY29kZSI6IkNaSyIsInVpZCI6IjIwMjAwMTAxMDAwMSIsImxhbmd1YWdlX2NvZGUiOiJjcyIsImlzX2RlcG9zaXQiOnRydWUsInNhdmVfYXV0aG9yaXphdGlvbiI6ZmFsc2UsImNhbl9jdXN0b21lcl9jaGFuZ2VfbWV0aG9kIjp0cnVlLCJtZXJjaGFudF9pZCI6Ijg2YTNlZWQwLTk1YTQtMTFlYS1hYzlmLTM3MWYzNDg4ZTBmYSIsInByb2plY3RfaWQiOjF9',
                '1b3e432dec35da90a62653e2cb8ce2d75e4204a32d360c6366afd24509f5178c',
            ],
            [
                new CreatePaymentParams(100, 'EUR', '202001010002'),
                'eyJhbW91bnQiOjEwMCwiY3VycmVuY3lfY29kZSI6IkVVUiIsInVpZCI6IjIwMjAwMTAxMDAwMiIsImxhbmd1YWdlX2NvZGUiOiJjcyIsImlzX2RlcG9zaXQiOnRydWUsInNhdmVfYXV0aG9yaXphdGlvbiI6ZmFsc2UsImNhbl9jdXN0b21lcl9jaGFuZ2VfbWV0aG9kIjp0cnVlLCJtZXJjaGFudF9pZCI6Ijg2YTNlZWQwLTk1YTQtMTFlYS1hYzlmLTM3MWYzNDg4ZTBmYSIsInByb2plY3RfaWQiOjF9',
                'b97e2a7c037c5d31a7db734d1ca84e06f53db5841fe9e56bdc701a833208f987',
            ],
        ];
    }

    public function testCreateCustomButton(): void
    {
        $r = $this->client->getPaymentButton(new CreatePaymentParams(100, 'CZK', '202001010003'));
        self::assertStringContainsString('Pay!', $r);
        self::assertStringContainsString('class="tp-btn"', $r);
        self::assertStringNotContainsString('data-payment-method', $r);
        $r = $this->client->getPaymentButton(new CreatePaymentParams(100, 'CZK', '202001010004'), 'Zaplatit!', true, 'bitcoin', ['class' => 'btn btn-success']);
        self::assertStringContainsString('Zaplatit!', $r);
        self::assertStringContainsString('class="tp-btn btn btn-success"', $r);
        self::assertStringContainsString('data-payment-method="bitcoin"', $r);
    }

    public function testGetPaymentMethods(): void
    {
        $this->apiService->method('getActivePaymentMethods')->willReturn(new PaymentMethodCollection([
            new PaymentMethod([
                'code' => 'test_method',
                'title' => 'TestTitle',
                'image' => [
                    'src' => 'https://example-image.com',
                ],
                'tags' => [],
                'available_currencies' => [
                    ['code' => 'CZK'],
                ],
            ]),
            new PaymentMethod([
                'code' => 'second_method',
                'title' => 'Second method',
                'image' => [
                    'src' => 'https://second-example-image.com',
                ],
                'tags' => [],
                'available_currencies' => [
                    ['code' => 'CZK'],
                ],
            ]),
            new PaymentMethod([
                'code' => 'incompatible_currency_method',
                'title' => 'Incompatible currency',
                'image' => [
                    'src' => 'https://incompatible-example-image.com',
                ],
                'tags' => [],
                'available_currencies' => [
                    ['code' => 'EUR'],
                ],
            ]),
        ]));

        $result = $this->client->getPaymentButtons(new CreatePaymentParams(100, 'CZK', '202001010005'));

        self::assertIsString($result);

        // In default we need to join assets
        self::assertStringContainsString('<style', $result);
        self::assertStringContainsString('<script', $result);

        // In default we want to send data through form post method, so we need form element
        self::assertStringContainsString('<form ', $result);

        self::assertStringContainsString('<img src="https://example-image.com"', $result);
        self::assertStringContainsString('<img src="https://second-example-image.com"', $result);
        self::assertStringContainsString('>TestTitle</span>', $result);
        self::assertStringContainsString('>Second method</span>', $result);
        self::assertStringContainsString('payment_method_code=test_method" data-thepay="payment-button"', $result);
        self::assertStringContainsString('payment_method_code=second_method" data-thepay="payment-button"', $result);

        self::assertStringNotContainsString('payment_method_code=incompatible_currency_method" data-thepay="payment-button"', $result);
    }

    public function testCreateApiPayment(): void
    {
        // Create entity with information about customer
        $customer = new CreatePaymentCustomer(
            'Mike',
            'Smith',
            'mike.smith@example.com',
            // Phone number in international format max 15 numeric chars https://en.wikipedia.org/wiki/MSISDN
            '420589687963',
            // Create billing address
            new Address('CZ', 'Prague', '123 00', 'Downstreet 5')
        );

        // Create payment (105.20 € with unique id uid123)
        $createPayment = new CreatePaymentParams(100, 'CZK', 'uid123');
        $createPayment->setOrderId('15478');
        $createPayment->setDescriptionForCustomer('Payment for items on example.com');
        $createPayment->setDescriptionForMerchant('Payment from VIP customer XYZ');
        $createPayment->setCustomer($customer);

        $this->apiService->method('createPayment')->willReturn(
            new CreatePaymentResponse(
                '{
                    "pay_url": "https://gate.thepay.cz/",
                    "detail_url": "https://gate.thepay.cz/"
                }'
            )
        );

        $result = $this->client->createPayment($createPayment);

        self::assertSame(CreatePaymentResponse::class, get_class($result));
    }
}
