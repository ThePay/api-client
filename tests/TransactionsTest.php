<?php

declare(strict_types=1);

namespace ThePay\ApiClient\Tests;

use ThePay\ApiClient\Filter\TransactionFilter;
use ThePay\ApiClient\Http\HttpServiceInterface;
use ThePay\ApiClient\Model\Collection\TransactionCollection;
use ThePay\ApiClient\Service\ApiServiceInterface;
use ThePay\ApiClient\TheClient;

final class TransactionsTest extends BaseTestCase
{
    private TheClient $client;

    protected function setUp(): void
    {
        parent::setUp();

        $httpService = $this->createMock(HttpServiceInterface::class);
        $apiService = $this->createMock(ApiServiceInterface::class);
        $apiService->method('getAccountTransactionHistory')->willReturn(
            new TransactionCollection(
                [
                    [
                        'transaction_id' => '35',
                        'amount' => 876.54,
                        'currency_code' => 'CZK',
                        'transaction_type' => 'payment',
                        'note' => 'Poznamka',
                        'realized_at' => '2021-05-01T12:00:00+00:00',
                        'vs' => '12365',
                        'ks' => '25',
                        'ss' => '3',
                        'offset_account' => [
                            'iban' => 'CZ65 0800 0000 1920 0014 5399',
                            'owner_name' => 'The Master',
                        ],
                        'payment_identificator' => [
                            'project_id' => '1',
                            'uid' => 'efd7d8e6-2fa3-3c46-b475-51762331bf56',
                        ],
                    ],
                    [
                        'transaction_id' => '78',
                        'amount' => 5666.54,
                        'currency_code' => 'CZK',
                        'transaction_type' => 'payment',
                        'note' => 'Poznamka',
                        'realized_at' => '2021-05-01T12:15:00+00:00',
                        'vs' => '12366',
                        'ks' => '25',
                        'ss' => '3',
                        'offset_account' => [
                            'iban' => 'CZ65 0800 0000 1920 0014 5399',
                            'owner_name' => 'The Master',
                        ],
                        'payment_identificator' => [
                            'project_id' => '1',
                            'uid' => 'efd7d8e6-2fa3-3c46-b475-51762331bf56',
                        ],
                    ],
                ],
                1,
                1,
                2
            )
        );

        $this->client = new TheClient($this->config, null, $httpService, $apiService);
    }

    public function testGettingTransactions(): void
    {
        $filter = new TransactionFilter('TP7811112150822790787055', new \DateTime('2021-01-01T12:30:00+00:00'), new \DateTime('2021-06-01T12:30:00+00:00'));
        $collection = $this->client->getAccountTransactionHistory($filter);

        self::assertCount(2, $collection->all());
    }

    public function testGettingTransactionsPaginatedCollection(): void
    {
        $filter = new TransactionFilter('TP7811112150822790787055', new \DateTime('2021-01-01T12:30:00+00:00'), new \DateTime('2021-06-01T12:30:00+00:00'));
        $collection = $this->client->getAccountTransactionHistory($filter);

        self::assertSame(2, $collection->getTotalCount());
    }
}
