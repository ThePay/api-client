<?php

declare(strict_types=1);

namespace ThePay\ApiClient\Tests;

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;
use ThePay\ApiClient\Service\ApiService;
use ThePay\ApiClient\Service\SignatureService;
use ThePay\ApiClient\TheClient;

final class ChangePaymentMethod extends BaseTestCase
{
    /** @var MockObject&ClientInterface */
    private MockObject $httpClient;

    private TheClient $client;

    protected function setUp(): void
    {
        parent::setUp();
        $this->httpClient = $this->createMock(ClientInterface::class);
        $apiService = new ApiService(
            $this->config,
            $this->createMock(SignatureService::class),
            $this->httpClient,
            $this->createMock(RequestFactoryInterface::class),
            $this->createMock(StreamFactoryInterface::class)
        );
        $this->client = new TheClient($this->config, $apiService);
    }

    public function testRequest(): void
    {
        $this->httpClient
            ->expects(self::once())
            ->method('sendRequest')
            ->with(
                new Request(
                    'PUT',
                    $this->config->getApiUrl() . 'projects/1/payments/abc/method?merchant_id=' . self::MERCHANT_ID,
                    [],
                    '{"payment_method_code":"transfer"}'
                )
            )
            ->willReturn($this->getOkResponse())
        ;

        $this->client->changePaymentMethod('abc', 'transfer');
    }

    public function testNotOkResponse(): void
    {
        $this->expectException(\Exception::class);

        $this->httpClient->method('sendRequest')->willReturn($this->getNotOkResponse());

        $this->client->changePaymentMethod('abc', 'transfer');
    }

    private function getOkResponse(): ResponseInterface
    {
        return new Response(204);
    }

    private function getNotOkResponse(): ResponseInterface
    {
        return new Response(404);
    }
}
