<?php

declare(strict_types=1);

namespace ThePay\ApiClient\Tests;

use ThePay\ApiClient\Model\RecurringPaymentResult;
use ThePay\ApiClient\Model\RealizeIrregularSubscriptionPaymentParams;
use ThePay\ApiClient\Model\RealizeRegularSubscriptionPaymentParams;
use ThePay\ApiClient\Model\RealizeUsageBasedSubscriptionPaymentParams;
use ThePay\ApiClient\Service\ApiServiceInterface;
use ThePay\ApiClient\TheClient;

final class RealizeSubscriptionPaymentTest extends BaseTestCase
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
        $apiService->method('realizeRegularSubscriptionPayment')->willReturn($okResponse);
        $apiService->method('realizeIrregularSubscriptionPayment')->willReturn($okResponse);
        $apiService->method('realizeUsageBasedSubscriptionPayment')->willReturn($okResponse);

        $this->client = new TheClient($this->config, $apiService);
    }

    public function testRealizeSubscriptionPayment(): void
    {
        $params = new RealizeRegularSubscriptionPaymentParams('childPayment');
        $result = $this->client->realizeRegularSubscriptionPayment('parentUid', $params);

        self::assertSame(RecurringPaymentResult::class, get_class($result));
        self::assertSame(RecurringPaymentResult::STATE_PAID, $result->getState());
        self::assertTrue($result->isRecurringPaymentsAvailable());

        $params = new RealizeIrregularSubscriptionPaymentParams('childPayment2');
        $result = $this->client->realizeIrregularSubscriptionPayment('parentUid', $params);

        self::assertSame(RecurringPaymentResult::class, get_class($result));
        self::assertSame(RecurringPaymentResult::STATE_PAID, $result->getState());

        $params = new RealizeUsageBasedSubscriptionPaymentParams('childPayment3', 1000);
        $result = $this->client->realizeUsageBasedSubscriptionPayment('parentUid', $params);

        self::assertSame(RecurringPaymentResult::class, get_class($result));
        self::assertSame(RecurringPaymentResult::STATE_PAID, $result->getState());
    }
}
