<?php

declare(strict_types=1);

namespace ThePay\ApiClient\Tests;

use GuzzleHttp\Psr7\HttpFactory;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use ThePay\ApiClient\Service\ApiService;
use ThePay\ApiClient\Service\SignatureService;
use ThePay\ApiClient\TheClient;

final class GeneratePaymentConfirmationTest extends BaseTestCase
{
    public function testSuccess(): void
    {
        $httpFactory = new HttpFactory();

        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient->expects(self::once())->method('sendRequest')
            ->willReturnCallback(function (RequestInterface $request): Response {
                self::assertSame('GET', $request->getMethod());
                self::assertSame(
                    $this->config->getApiUrl() . 'projects/1/payments/testUID/generate_confirmation?language=cs&merchant_id=' . self::MERCHANT_ID,
                    $request->getUri()->__toString()
                );

                return new Response(200, [], 'test pdf content');
            })
        ;

        $theClient = new TheClient(
            $this->config,
            new ApiService(
                $this->config,
                $this->createMock(SignatureService::class),
                $httpClient,
                $httpFactory,
                $httpFactory
            )
        );

        $pdfContent = $theClient->generatePaymentConfirmationPdf('testUID', 'cs');

        self::assertSame('test pdf content', $pdfContent);
    }

    public function testFailed(): void
    {
        $httpFactory = new HttpFactory();

        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient->expects(self::once())->method('sendRequest')
            ->willReturn(new Response(400));

        $theClient = new TheClient(
            $this->config,
            new ApiService(
                $this->config,
                $this->createMock(SignatureService::class),
                $httpClient,
                $httpFactory,
                $httpFactory
            )
        );

        $this->expectException(\RuntimeException::class);

        $theClient->generatePaymentConfirmationPdf('testUID', 'cs');
    }
}
