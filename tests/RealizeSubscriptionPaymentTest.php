<?php

declare(strict_types=1);

namespace ThePay\ApiClient\Tests;

use ThePay\ApiClient\Http\HttpServiceInterface;
use ThePay\ApiClient\Model\ApiResponse;
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

        $httpService = $this->createMock(HttpServiceInterface::class);

        $okResponse = new ApiResponse(
            '{
                "state": "success",
                "message": "Ok"
            }',
            200
        );

        $apiService = $this->createMock(ApiServiceInterface::class);
        $apiService->method('realizeRegularSubscriptionPayment')->willReturn($okResponse);
        $apiService->method('realizeIrregularSubscriptionPayment')->willReturn($okResponse);
        $apiService->method('realizeUsageBasedSubscriptionPayment')->willReturn($okResponse);

        $this->client = new TheClient($this->config, null, $httpService, $apiService);
    }

    public function testRealizeSubscriptionPayment(): void
    {
        $params = new RealizeRegularSubscriptionPaymentParams('childPayment');
        $result = $this->client->realizeRegularSubscriptionPayment('parentUid', $params);

        self::assertSame(ApiResponse::class, get_class($result));

        $params = new RealizeIrregularSubscriptionPaymentParams('childPayment2');
        $result = $this->client->realizeIrregularSubscriptionPayment('parentUid', $params);

        self::assertSame(ApiResponse::class, get_class($result));

        $params = new RealizeUsageBasedSubscriptionPaymentParams('childPayment3', 1000);
        $result = $this->client->realizeUsageBasedSubscriptionPayment('parentUid', $params);

        self::assertSame(ApiResponse::class, get_class($result));
    }
}
