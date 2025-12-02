<?php

declare(strict_types=1);

namespace ThePay\ApiClient\Tests;

use ThePay\ApiClient\Model\RecurringPaymentResult;
use ThePay\ApiClient\Model\RealizePaymentBySavedAuthorizationParams;
use ThePay\ApiClient\Service\ApiServiceInterface;
use ThePay\ApiClient\TheClient;

final class RealizeSavedAuthorizationPaymentTest extends BaseTestCase
{
    private TheClient $client;

    protected function setUp(): void
    {
        parent::setUp();

        $okResponse = new RecurringPaymentResult(
            '{
                "state": "paid",
                "message": "Ok",
                "parent": {
                    "recurring_payments_available": true
                }
            }'
        );

        $apiService = $this->createMock(ApiServiceInterface::class);
        $apiService->method('realizePaymentBySavedAuthorization')->willReturn($okResponse);

        $this->client = new TheClient($this->config, $apiService);
    }

    public function testRealizePaymentBySavedAuthorization(): void
    {
        $params = new RealizePaymentBySavedAuthorizationParams('childPayment', 10000, 'CZK');
        $result = $this->client->realizePaymentBySavedAuthorization('parentUid', $params);

        self::assertSame(RecurringPaymentResult::class, get_class($result));
        self::assertSame(RecurringPaymentResult::STATE_PAID, $result->getState());
        self::assertTrue($result->isRecurringPaymentsAvailable());
    }
}
