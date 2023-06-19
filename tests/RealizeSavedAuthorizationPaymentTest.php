<?php

declare(strict_types=1);

namespace ThePay\ApiClient\Tests;

use ThePay\ApiClient\Http\HttpServiceInterface;
use ThePay\ApiClient\Model\ApiResponse;
use ThePay\ApiClient\Model\RealizePaymentBySavedAuthorizationParams;
use ThePay\ApiClient\Tests\Mocks\Service\ApiMockService;
use ThePay\ApiClient\TheClient;

final class RealizeSavedAuthorizationPaymentTest extends BaseTestCase
{
    private TheClient $client;

    protected function setUp(): void
    {
        parent::setUp();

        $httpService = $this->createMock(HttpServiceInterface::class);
        $apiService = new ApiMockService($this->config, $httpService);

        $this->client = new TheClient($this->config, null, $httpService, $apiService);
    }

    public function testRealizePaymentBySavedAuthorization(): void
    {
        $params = new RealizePaymentBySavedAuthorizationParams('childPayment');
        $result = $this->client->realizePaymentBySavedAuthorization('parentUid', $params);

        self::assertSame(ApiResponse::class, get_class($result));
    }
}
