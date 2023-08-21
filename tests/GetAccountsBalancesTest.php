<?php

declare(strict_types=1);

namespace ThePay\ApiClient\Tests;

use GuzzleHttp\Psr7\HttpFactory;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use ThePay\ApiClient\Model\AccountBalance;
use ThePay\ApiClient\Service\ApiService;
use ThePay\ApiClient\Service\SignatureService;
use ThePay\ApiClient\TheClient;

final class GetAccountsBalancesTest extends BaseTestCase
{
    public function test(): void
    {
        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient
            ->expects(self::once())
            ->method('sendRequest')
            ->willReturnCallback(function (RequestInterface $request): ResponseInterface {
                $expectedUrl = $this->config->getApiUrl() . 'balances?account_iban=TP7811112150822790787055&project_id=1&balance_at=2023-03-14T15%3A08%3A44%2B00%3A00&merchant_id=' . self::MERCHANT_ID;

                self::assertSame('GET', $request->getMethod());
                self::assertSame($expectedUrl, $request->getUri()->__toString());

                return new Response(
                    200,
                    [],
                    '[
                        {
                            "iban": "TP7811112150822790787055",
                            "name": "Test",
                            "balance": {
                                "CZK": "45899",
                                "EUR": "500"
                            }
                        }
                    ]'
                );
            });

        $httpFactory = new HttpFactory();
        $apiService = new ApiService(
            $this->config,
            $this->createMock(SignatureService::class),
            $httpClient,
            $httpFactory,
            $httpFactory
        );
        $client = new TheClient($this->config, $apiService);

        $balances = $client->getAccountsBalances('TP7811112150822790787055', 1, new \DateTime('2023-03-14 15:08:44+00:00'));
        self::assertEquals(
            [
                new AccountBalance(
                    'TP7811112150822790787055',
                    'Test',
                    ['CZK' => '45899', 'EUR' => '500']
                ),
            ],
            $balances
        );
    }
}
