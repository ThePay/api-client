<?php

declare(strict_types=1);

namespace ThePay\ApiClient\Tests;

use GuzzleHttp\Psr7\HttpFactory;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use ThePay\ApiClient\Service\ApiService;
use ThePay\ApiClient\Service\SignatureService;
use ThePay\ApiClient\TheClient;

final class CancelPreauthorizationPaymentTest extends BaseTestCase
{
    /** @var MockObject&ClientInterface */
    private MockObject $httpClient;
    private TheClient $client;

    protected function setUp(): void
    {
        parent::setUp();
        $this->httpClient = $this->createMock(ClientInterface::class);
        $httpFactory = new HttpFactory();
        $apiService = new ApiService(
            $this->config,
            $this->createMock(SignatureService::class),
            $this->httpClient,
            $httpFactory,
            $httpFactory
        );
        $this->client = new TheClient($this->config, $apiService);
    }

    public function testRequest(): void
    {
        $this->httpClient
            ->expects(self::once())
            ->method('sendRequest')
            ->willReturnCallback(function (RequestInterface $request): ResponseInterface {
                $expectedUrl = $this->config->getApiUrl() . 'projects/1/payments/abc/preauthorized?merchant_id=' . self::MERCHANT_ID;

                self::assertSame('DELETE', $request->getMethod());
                self::assertSame($expectedUrl, $request->getUri()->__toString());

                return $this->getOkResponse();
            })
        ;

        $this->client->cancelPreauthorizedPayment('abc');
    }

    public function testNotOkResponse(): void
    {
        $this->expectException(\Exception::class);

        $this->httpClient->method('sendRequest')->willReturn($this->getNotOkResponse());

        $this->client->cancelPreauthorizedPayment('abc');
    }

    private function getOkResponse(): ResponseInterface
    {
        return new Response(204);
    }

    private function getNotOkResponse(): ResponseInterface
    {
        return new Response(401);
    }
}
