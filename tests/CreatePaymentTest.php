<?php

declare(strict_types=1);

namespace ThePay\ApiClient\Tests;

use PHPUnit\Framework\MockObject\MockObject;
use ThePay\ApiClient\Model\Address;
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

        $this->apiService = $this->createMock(ApiServiceInterface::class);
        $this->client = new TheClient($this->config, $this->apiService);
    }

    /**
     * @return array<array<mixed>>
     */
    public static function createButtonProvider(): array
    {
        return [
            [
                new CreatePaymentParams(100, 'CZK', '202001010001', self::getCreatePaymentCustomer()),
                'eyJhbW91bnQiOjEwMCwiY3VycmVuY3lfY29kZSI6IkNaSyIsInVpZCI6IjIwMjAwMTAxMDAwMSIsImxhbmd1YWdlX2NvZGUiOiJjcyIsImlzX2RlcG9zaXQiOnRydWUsInNhdmVfYXV0aG9yaXphdGlvbiI6ZmFsc2UsImNhbl9jdXN0b21lcl9jaGFuZ2VfbWV0aG9kIjp0cnVlLCJjdXN0b21lcl9uYW1lIjoiTWlrZSIsImN1c3RvbWVyX3N1cm5hbWUiOiJTbWl0aCIsImN1c3RvbWVyX2VtYWlsIjoibWlrZS5zbWl0aEB1bml2ZXJzYWwtYWNjZXB0YW5jZS10ZXN0LmljdSIsImN1c3RvbWVyX3Bob25lIjoiNDIwNTg5Njg3OTYzIiwiY3VzdG9tZXJfYmlsbGluZ19jb3VudHJ5X2NvZGUiOiJDWiIsImN1c3RvbWVyX2JpbGxpbmdfY2l0eSI6IlByYWd1ZSIsImN1c3RvbWVyX2JpbGxpbmdfemlwIjoiMTIzIDAwIiwiY3VzdG9tZXJfYmlsbGluZ19zdHJlZXQiOiJEb3duc3RyZWV0IDUiLCJtZXJjaGFudF9pZCI6Ijg2YTNlZWQwLTk1YTQtMTFlYS1hYzlmLTM3MWYzNDg4ZTBmYSIsInByb2plY3RfaWQiOjF9',
                '583da369ef16a57119975981a86407693f22875b0b27804769cf08ecbf0ee16c',
            ],
            [
                new CreatePaymentParams(100, 'EUR', '202001010002', self::getCreatePaymentCustomer()),
                'eyJhbW91bnQiOjEwMCwiY3VycmVuY3lfY29kZSI6IkVVUiIsInVpZCI6IjIwMjAwMTAxMDAwMiIsImxhbmd1YWdlX2NvZGUiOiJjcyIsImlzX2RlcG9zaXQiOnRydWUsInNhdmVfYXV0aG9yaXphdGlvbiI6ZmFsc2UsImNhbl9jdXN0b21lcl9jaGFuZ2VfbWV0aG9kIjp0cnVlLCJjdXN0b21lcl9uYW1lIjoiTWlrZSIsImN1c3RvbWVyX3N1cm5hbWUiOiJTbWl0aCIsImN1c3RvbWVyX2VtYWlsIjoibWlrZS5zbWl0aEB1bml2ZXJzYWwtYWNjZXB0YW5jZS10ZXN0LmljdSIsImN1c3RvbWVyX3Bob25lIjoiNDIwNTg5Njg3OTYzIiwiY3VzdG9tZXJfYmlsbGluZ19jb3VudHJ5X2NvZGUiOiJDWiIsImN1c3RvbWVyX2JpbGxpbmdfY2l0eSI6IlByYWd1ZSIsImN1c3RvbWVyX2JpbGxpbmdfemlwIjoiMTIzIDAwIiwiY3VzdG9tZXJfYmlsbGluZ19zdHJlZXQiOiJEb3duc3RyZWV0IDUiLCJtZXJjaGFudF9pZCI6Ijg2YTNlZWQwLTk1YTQtMTFlYS1hYzlmLTM3MWYzNDg4ZTBmYSIsInByb2plY3RfaWQiOjF9',
                '7f7ee7a177bdd4cee0287283a2c497f0511a76a89464e52ae72b0b372c7be6ce',
            ],
        ];
    }

    public function testCreateApiPayment(): void
    {
        // Create payment (105.20 € with unique id uid123)
        $createPayment = new CreatePaymentParams(100, 'CZK', 'uid123', self::getCreatePaymentCustomer());
        $createPayment->setOrderId('15478');
        $createPayment->setDescriptionForCustomer('Payment for items on example.com');
        $createPayment->setDescriptionForMerchant('Payment from VIP customer XYZ');

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

    private static function getCreatePaymentCustomer(): CreatePaymentCustomer
    {
        return new CreatePaymentCustomer(
            'Mike',
            'Smith',
            'mike.smith@universal-acceptance-test.icu',
            // Phone number in international format max 15 numeric chars https://en.wikipedia.org/wiki/MSISDN
            '420589687963',
            // Create billing address
            new Address('CZ', 'Prague', '123 00', 'Downstreet 5')
        );
    }
}
