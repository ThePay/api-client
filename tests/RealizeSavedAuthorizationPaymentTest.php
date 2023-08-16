<?php

declare(strict_types=1);

namespace ThePay\ApiClient\Tests;

use ThePay\ApiClient\Model\ApiResponse;
use ThePay\ApiClient\Model\RealizePaymentBySavedAuthorizationParams;
use ThePay\ApiClient\Service\ApiServiceInterface;
use ThePay\ApiClient\TheClient;

final class RealizeSavedAuthorizationPaymentTest extends BaseTestCase
{
    private TheClient $client;

    protected function setUp(): void
    {
        parent::setUp();

        $okResponse = new ApiResponse(
            '{
                "state": "success",
                "message": "Ok"
            }',
            200
        );

        $apiService = $this->createMock(ApiServiceInterface::class);
        $apiService->method('realizePaymentBySavedAuthorization')->willReturn($okResponse);

        $this->client = new TheClient($this->config, $apiService);
    }

    public function testRealizePaymentBySavedAuthorization(): void
    {
        $params = new RealizePaymentBySavedAuthorizationParams('childPayment');
        $result = $this->client->realizePaymentBySavedAuthorization('parentUid', $params);

        self::assertSame(ApiResponse::class, get_class($result));
    }
}
