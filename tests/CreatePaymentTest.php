<?php

declare(strict_types=1);

namespace ThePay\ApiClient\Tests;

use PHPUnit\Framework\MockObject\MockObject;
use ThePay\ApiClient\Http\HttpServiceInterface;
use ThePay\ApiClient\Model\Address;
use ThePay\ApiClient\Model\Collection\PaymentMethodCollection;
use ThePay\ApiClient\Model\CreatePaymentCustomer;
use ThePay\ApiClient\Model\CreatePaymentParams;
use ThePay\ApiClient\Model\CreatePaymentResponse;
use ThePay\ApiClient\Service\ApiServiceInterface;
use ThePay\ApiClient\TheClient;

final class CreatePaymentTest extends BaseTestCase
{
    private TheClient $client;

    private MockObject $apiService;

    protected function setUp(): void
    {
        parent::setUp();

        $httpService = $this->createMock(HttpServiceInterface::class);
        $this->apiService = $this->createMock(ApiServiceInterface::class);
        $this->client = new TheClient($this->config, null, $httpService, $this->apiService);
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
                'eyJhbW91bnQiOjEwMCwiY3VycmVuY3lfY29kZSI6IkNaSyIsInVpZCI6IjIwMjAwMTAxMDAwMSIsImxhbmd1YWdlX2NvZGUiOiJjcyIsImlzX3JlY3VycmluZyI6ZmFsc2UsImlzX2RlcG9zaXQiOnRydWUsInNhdmVfYXV0aG9yaXphdGlvbiI6ZmFsc2UsImNhbl9jdXN0b21lcl9jaGFuZ2VfbWV0aG9kIjp0cnVlLCJtZXJjaGFudF9pZCI6Ijg2YTNlZWQwLTk1YTQtMTFlYS1hYzlmLTM3MWYzNDg4ZTBmYSIsInByb2plY3RfaWQiOjF9',
                '99e8b5f1bb32cd6937af1ebbcc7d3b337b7f5524f244a5d694c930afef1a16a9',
            ],
            [
                new CreatePaymentParams(100, 'EUR', '202001010002'),
                'eyJhbW91bnQiOjEwMCwiY3VycmVuY3lfY29kZSI6IkVVUiIsInVpZCI6IjIwMjAwMTAxMDAwMiIsImxhbmd1YWdlX2NvZGUiOiJjcyIsImlzX3JlY3VycmluZyI6ZmFsc2UsImlzX2RlcG9zaXQiOnRydWUsInNhdmVfYXV0aG9yaXphdGlvbiI6ZmFsc2UsImNhbl9jdXN0b21lcl9jaGFuZ2VfbWV0aG9kIjp0cnVlLCJtZXJjaGFudF9pZCI6Ijg2YTNlZWQwLTk1YTQtMTFlYS1hYzlmLTM3MWYzNDg4ZTBmYSIsInByb2plY3RfaWQiOjF9',
                '03e61b053d5b0ccd17a2913a5b005e35fd499b74472adec92e792eecdd123895',
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
        $this->apiService->method('getActivePaymentMethods')->willReturn(new PaymentMethodCollection([]));

        $result = $this->client->getPaymentButtons(new CreatePaymentParams(100, 'CZK', '202001010005'));

        self::assertIsString($result);

        // In default we need to join assets
        self::assertStringContainsString('<style', $result);
        self::assertStringContainsString('<script', $result);

        // In default we want to send data through form post method, so we need form element
        self::assertStringContainsString('<form ', $result);


        // todo: complete test, implementation was not final at this moment
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
