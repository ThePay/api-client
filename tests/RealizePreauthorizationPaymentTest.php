<?php

declare(strict_types=1);

namespace ThePay\ApiClient\Tests;

use PHPUnit\Framework\MockObject\MockObject;
use ThePay\ApiClient\Http\HttpResponse;
use ThePay\ApiClient\Http\HttpServiceInterface;
use ThePay\ApiClient\Model\RealizePreauthorizedPaymentParams;
use ThePay\ApiClient\Service\ApiService;
use ThePay\ApiClient\TheClient;

final class RealizePreauthorizationPaymentTest extends BaseTestCase
{
    /** @var MockObject&HttpServiceInterface */
    private MockObject $httpService;
    private TheClient $client;

    /**
     * @return void
     */
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
            ->method('post')
            ->with(
                $this->config->getApiUrl() . 'projects/1/payments/abc/preauthorized?merchant_id=' . self::MERCHANT_ID,
                '{"amount":100}',
            )
            ->willReturn($this->getOkResponse())
        ;

        $this->client->realizePreauthorizedPayment(new RealizePreauthorizedPaymentParams(100, 'abc'));
    }

    public function testNotOkResponse(): void
    {
        $this->expectException(\Exception::class);

        $this->httpService->method('post')->willReturn($this->getNotOkResponse());

        $this->client->realizePreauthorizedPayment(new RealizePreauthorizedPaymentParams(100, 'abc'));
    }

    private function getOkResponse(): HttpResponse
    {
        return new HttpResponse(null, 204);
    }

    private function getNotOkResponse(): HttpResponse
    {
        return new HttpResponse(null, 401);
    }
}
