<?php

declare(strict_types=1);

namespace ThePay\ApiClient\Tests;

use ThePay\ApiClient\Filter\PaymentsFilter;
use ThePay\ApiClient\Http\HttpServiceInterface;
use ThePay\ApiClient\Model\Collection\PaymentCollection;
use ThePay\ApiClient\Model\Payment;
use ThePay\ApiClient\Service\ApiServiceInterface;
use ThePay\ApiClient\TheClient;

final class PaymentsTest extends BaseTestCase
{
    private TheClient $client;

    protected function setUp(): void
    {
        parent::setUp();

        $httpService = $this->createMock(HttpServiceInterface::class);
        $apiService = $this->createMock(ApiServiceInterface::class);
        $apiService->method('getPayment')->willReturn(
            new Payment(
                [
                    'uid' => 'efd7d8e6-2fa3-3c46-b475-51762331bf56',
                    'project_id' => 1,
                    'state' => 'paid',
                    'currency' => 'CZK',
                    'amount' => 876.54,
                    'created_at' => '2019-01-01T12:00:00+00:00',
                    'finished_at' => '2019-01-01T12:00:00+00:00',
                    'valid_to' => '2019-01-01T12:00:00+00:00',
                    'fee' => 12.1,
                    'description' => 'Some sort of description',
                    'description_for_merchant' => 'My internal description',
                    'order_id' => 'CZ12131415',
                    'pay_url' => 'http://example.com',
                    'detail_url' => 'http://example.com',
                    'offset_account_determined_at' => null,
                    'payment_method' => 'card',
                    'offset_account_status' => 'loaded',
                    'offset_account' => [
                        'iban' => 'CZ65 0800 0000 1920 0014 5399',
                        'raw_account_number' => '1111/2010',
                        'owner_name' => 'The Master',
                    ],
                    'customer' => [
                        'account_iban' => 'CZ65 0800 0000 1920 0014 5399',
                        'name' => 'The Customer',
                        'ip' => '192.168.0.1',
                        'email' => '',
                    ],
                    'events' => [
                        [
                            'occured_at' => '2021-05-03T10:23:27.000000Z',
                            'type' => 'payment_cancelled',
                            'data' => null,
                        ],
                    ],
                ]
            )
        );
        $apiService->method('getPayments')->willReturn(
            new PaymentCollection(
                [
                    [
                        'uid' => 'efd7d8e6-2fa3-3c46-b475-51762331bf56',
                        'project_id' => 1,
                        'state' => 'paid',
                        'currency' => 'CZK',
                        'amount' => 876.54,
                        'created_at' => '2019-01-01T12:00:00+00:00',
                        'finished_at' => '2019-01-01T12:00:00+00:00',
                        'valid_to' => '2019-01-01T12:00:00+00:00',
                        'fee' => 12.1,
                        'description' => 'Some sort of description',
                        'description_for_merchant' => 'My internal description',
                        'pay_url' => 'http://example.com',
                        'detail_url' => 'http://example.com',
                        'order_id' => 'CZ12131415',
                        'payment_method' => 'card',
                        'offset_account_determined_at' => null,
                        'offset_account_status' => 'loaded',
                        'offset_account' =>
                            [
                                'iban' => 'CZ65 0800 0000 1920 0014 5399',
                                'raw_account_number' => '1111/2010',
                                'owner_name' => 'The Master',
                            ],
                        'customer' =>
                            [
                                'account_iban' => 'CZ65 0800 0000 1920 0014 5399',
                                'name' => 'The Customer',
                                'ip' => '192.168.0.1',
                                'email' => '',
                            ],
                        'events' =>
                            [
                                [
                                    'occured_at' => '2021-05-03T10:23:27.000000Z',
                                    'type' => 'payment_cancelled',
                                    'data' => null,
                                ],
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

    public function testGettingPayments(): void
    {
        $filter = new PaymentsFilter();
        $collection = $this->client->getPayments($filter);

        self::assertCount(1, $collection->all());
    }

    public function testGettingPaymentsPaginatedCollection(): void
    {
        $filter = new PaymentsFilter();
        $collection = $this->client->getPayments($filter);

        self::assertSame(2, $collection->getTotalCount());
    }

    public function testGetPayment(): void
    {
        $payment = $this->client->getPayment('test-UID');

        self::assertSame('efd7d8e6-2fa3-3c46-b475-51762331bf56', $payment->getUid());
    }

    public function testGetPaymentNullUid(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Payment UID cannot be null.');

        /** @phpstan-ignore-next-line */
        $this->client->getPayment(null);
    }

    public function testGetPaymentEmptyUid(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Payment UID cannot be empty string.');

        $this->client->getPayment('');
    }

    public function testGetPaymentUidNotStringable(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Payment UID cannot be converted to string.');

        /** @phpstan-ignore-next-line */
        $this->client->getPayment(new \stdClass());
    }
}
