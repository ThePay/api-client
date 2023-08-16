<?php

declare(strict_types=1);

namespace ThePay\ApiClient\Tests;

use GuzzleHttp\Psr7\HttpFactory;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use ThePay\ApiClient\Model\RealizePreauthorizedPaymentParams;
use ThePay\ApiClient\Service\ApiService;
use ThePay\ApiClient\Service\SignatureService;
use ThePay\ApiClient\TheClient;

final class RealizePreauthorizationPaymentTest extends BaseTestCase
{
    /** @var MockObject&ClientInterface */
    private MockObject $httpClient;
    private TheClient $client;

    /**
     * @return void
     */
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

                self::assertSame('POST', $request->getMethod());
                self::assertSame($expectedUrl, $request->getUri()->__toString());
                self::assertSame('{"amount":100}', $request->getBody()->getContents());

                return $this->getOkResponse();
            })
        ;

        $this->client->realizePreauthorizedPayment(new RealizePreauthorizedPaymentParams(100, 'abc'));
    }

    public function testNotOkResponse(): void
    {
        $this->expectException(\Exception::class);

        $this->httpClient->method('sendRequest')->willReturn($this->getNotOkResponse());

        $this->client->realizePreauthorizedPayment(new RealizePreauthorizedPaymentParams(100, 'abc'));
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
