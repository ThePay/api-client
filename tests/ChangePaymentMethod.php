<?php

declare(strict_types=1);

namespace ThePay\ApiClient\Tests;

use PHPUnit\Framework\MockObject\MockObject;
use ThePay\ApiClient\Http\HttpResponse;
use ThePay\ApiClient\Http\HttpServiceInterface;
use ThePay\ApiClient\Service\ApiService;
use ThePay\ApiClient\TheClient;

final class ChangePaymentMethod extends BaseTestCase
{
    /** @var MockObject&HttpServiceInterface */
    private MockObject $httpService;

    private TheClient $client;

    protected function setUp(): void
    {
        parent::setUp();
        $this->httpService = $this->createMock(HttpServiceInterface::class);
        $apiService = new ApiService($this->config, $this->httpService);
        $this->client = new TheClient($this->config, null, $this->httpService, $apiService);
    }

    public function testRequest(): void
    {
        $this->httpService
            ->expects(self::once())
            ->method('put')
            ->with(
                $this->config->getApiUrl() . 'projects/1/payments/abc/method?merchant_id=' . self::MERCHANT_ID,
                '{"payment_method_code":"transfer"}',
            )
            ->willReturn($this->getOkResponse())
        ;

        $this->client->changePaymentMethod('abc', 'transfer');
    }

    public function testNotOkResponse(): void
    {
        $this->expectException(\Exception::class);

        $this->httpService->method('delete')->willReturn($this->getNotOkResponse());

        $this->client->changePaymentMethod('abc', 'transfer');
    }

    private function getOkResponse(): HttpResponse
    {
        return new HttpResponse(null, 204);
    }

    private function getNotOkResponse(): HttpResponse
    {
        return new HttpResponse(null, 404);
    }
}
