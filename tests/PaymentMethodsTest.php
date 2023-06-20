<?php

declare(strict_types=1);

namespace ThePay\ApiClient\Tests;

use ThePay\ApiClient\Filter\PaymentMethodFilter;
use ThePay\ApiClient\Http\HttpServiceInterface;
use ThePay\ApiClient\Tests\Mocks\Service\ApiMockService;
use ThePay\ApiClient\TheClient;
use ThePay\ApiClient\ValueObject\PaymentMethodTag;

final class PaymentMethodsTest extends BaseTestCase
{
    private TheClient $client;

    protected function setUp(): void
    {
        parent::setUp();

        $httpService = $this->createMock(HttpServiceInterface::class);
        $apiService = new ApiMockService($this->config, $httpService);
        $this->client = new TheClient($this->config, null, $httpService, $apiService);
    }

    public function testGettingActivePaymentMethods(): void
    {
        $client = $this->getApiaryClient();

        $methods = $client->getActivePaymentMethods();
        $cardMethod = $methods->get('card');

        self::assertSame(1, $methods->size());
        self::assertNotNull($cardMethod);
        self::assertSame('card', $cardMethod->getCode());
        self::assertSame('Card Payment', $cardMethod->getTitle());
        self::assertSame(['online', 'returnable'], $cardMethod->getTags());
        self::assertSame(['CZK'], $cardMethod->getAvailableCurrencies());
        self::assertSame('https://neco.cz', $cardMethod->getImageUrl()->getValue());
    }

    /**
     * This test verifies that doubling payment method tags in filters has no effect for filtered payment methods.
     *
     * @param array<string> $usedTags
     * @param bool $isRecurring This parameter causes adding of recurring_payments tag
     * @param bool $isNotDeposit This parameter causes adding of pre_authorization tag
     *
     * @dataProvider paymentMethodsFilterDoublingUsedTagsDataProvider
     */
    public function testPaymentMethodsFilterDoublingUsedTags(
        string $expectedMethod,
        array $usedTags,
        bool $isRecurring,
        bool $isNotDeposit
    ): void {
        $filter = new PaymentMethodFilter([], $usedTags, []);

        $methods = $this->client->getActivePaymentMethods()
            ->getFiltered($filter, $isRecurring, ! $isNotDeposit);

        self::assertNotNull($methods->get($expectedMethod));
    }

    public function testPaymentMethodsFilterDoublingBannedTags(): void
    {
        $filter = new PaymentMethodFilter([], [], [PaymentMethodTag::PRE_AUTHORIZATION, PaymentMethodTag::PRE_AUTHORIZATION]);

        $methods = $this->client->getActivePaymentMethods()
            ->getFiltered($filter);

        self::assertNull($methods->get(PaymentMethodTag::CARD));
    }

    public function testPaymentMethodsFilterDoublingCurrencies(): void
    {
        $filter = new PaymentMethodFilter(['CZK', 'CZK'], [], []);

        $methods = $this->client->getActivePaymentMethods()
            ->getFiltered($filter);

        self::assertNotNull($methods->get(PaymentMethodTag::CARD));
    }

    /**
     * @return array<array<mixed>>
     */
    public static function paymentMethodsFilterDoublingUsedTagsDataProvider(): array
    {
        return [
            [
                'card',
                [
                    PaymentMethodTag::PRE_AUTHORIZATION,
                ],
                false,
                true,
            ],
            [
                'card',
                [
                    PaymentMethodTag::RECURRING_PAYMENTS,
                ],
                true,
                false,
            ],
            [
                'card',
                [
                    PaymentMethodTag::PRE_AUTHORIZATION,
                    PaymentMethodTag::PRE_AUTHORIZATION,
                ],
                false,
                true,
            ],
            [
                'card',
                [
                    PaymentMethodTag::RECURRING_PAYMENTS,
                    PaymentMethodTag::RECURRING_PAYMENTS,
                ],
                true,
                false,
            ],
            [
                'card',
                [
                    PaymentMethodTag::PRE_AUTHORIZATION,
                    PaymentMethodTag::RECURRING_PAYMENTS,
                    PaymentMethodTag::PRE_AUTHORIZATION,
                    PaymentMethodTag::RECURRING_PAYMENTS,
                ],
                true,
                true,
            ],
        ];
    }

    /**
     * @dataProvider filterDataProvider
     */
    public function testFiltering(
        PaymentMethodFilter $filter,
        int $result,
        bool $isRecurring = false,
        bool $isDeposit = true
    ): void {
        $methods = $this->client->getActivePaymentMethods($filter, null, $isRecurring, $isDeposit);

        self::assertSame($result, $methods->size());
    }

    /**
     * @return array<array<mixed>>
     */
    public static function filterDataProvider(): array
    {
        return [
            [
                new PaymentMethodFilter(
                    ['GBP'],
                    [],
                    []
                ),
                1,
            ],
            [
                new PaymentMethodFilter(
                    [],
                    ['online'],
                    []
                ),
                7,
            ],
            [
                new PaymentMethodFilter(
                    [],
                    ['online'],
                    ['access_account_owner']
                ),
                2,
            ],
            [
                new PaymentMethodFilter(
                    [],
                    ['online'],
                    ['access_account_owner', 'alternative_method']
                ),
                1,
            ],
            [
                new PaymentMethodFilter([], [], []),
                1,
                true,
            ],
            [
                new PaymentMethodFilter([], [], []),
                1,
                false,
                false,
            ],
        ];
    }
}
