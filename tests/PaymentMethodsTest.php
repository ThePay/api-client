<?php

namespace ThePay\ApiClient\Tests;

use Mockery;
use ThePay\ApiClient\Filter\PaymentMethodFilter;
use ThePay\ApiClient\Tests\Mocks\Service\ApiMockService;
use ThePay\ApiClient\TheClient;
use ThePay\ApiClient\ValueObject\PaymentMethodCode;
use ThePay\ApiClient\ValueObject\PaymentMethodTag;

class PaymentMethodsTest extends BaseTestCase
{
    /** @var TheClient */
    private $client;

    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $httpService = Mockery::mock('ThePay\ApiClient\Http\HttpServiceInterface');
        /** @phpstan-ignore-next-line */
        $apiService = new ApiMockService($this->config, $httpService);
        /** @phpstan-ignore-next-line */
        $this->client = new TheClient($this->config, null, $httpService, $apiService);
    }

    /**
     * @return void
     */
    public function testGettingActivePaymentMethods()
    {
        $client = $this->getApiaryClient();

        $methods = $client->getActivePaymentMethods();
        $cardMethod = $methods->get('card');

        static::assertSame(1, $methods->size());
        static::assertNotNull($cardMethod);
        static::assertSame('card', $cardMethod->getCode());
        static::assertSame('Card Payment', $cardMethod->getTitle());
        static::assertSame(array('online', 'returnable'), $cardMethod->getTags());
        static::assertSame(array('CZK'), $cardMethod->getAvailableCurrencies());
        static::assertSame('https://neco.cz', $cardMethod->getImageUrl()->getValue());
    }

    /**
     * This test verifies that doubling payment method tags in filters has no effect for filtered payment methods.
     *
     * @param string $expectedMethod
     * @param array<string> $usedTags
     * @param bool $isRecurring This parameter causes adding of recurring_payments tag
     * @param bool $isNotDeposit This parameter causes adding of pre_authorization tag
     * @return void
     *
     * @throws \ThePay\ApiClient\Exception\ApiException
     *
     * @dataProvider paymentMethodsFilterDoublingUsedTagsDataProvider
     */
    public function testPaymentMethodsFilterDoublingUsedTags($expectedMethod, array $usedTags, $isRecurring, $isNotDeposit)
    {
        $filter = new PaymentMethodFilter(array(), $usedTags, array());

        $methods = $this->client->getActivePaymentMethods()
            ->getFiltered($filter, $isRecurring, ! $isNotDeposit);

        self::assertNotNull($methods->get($expectedMethod));
    }

    /**
     * @return void
     */
    public function testPaymentMethodsFilterDoublingBannedTags()
    {
        $filter = new PaymentMethodFilter(array(), array(), array(PaymentMethodTag::PRE_AUTHORIZATION, PaymentMethodTag::PRE_AUTHORIZATION));

        $methods = $this->client->getActivePaymentMethods()
            ->getFiltered($filter);

        self::assertNull($methods->get(PaymentMethodTag::CARD));
    }

    /**
     * @return void
     */
    public function testPaymentMethodsFilterDoublingCurrencies()
    {
        $filter = new PaymentMethodFilter(array('CZK', 'CZK'), array(), array());

        $methods = $this->client->getActivePaymentMethods()
            ->getFiltered($filter);

        self::assertNotNull($methods->get(PaymentMethodTag::CARD));
    }

    /**
     * @return array<array<mixed>>
     */
    public function paymentMethodsFilterDoublingUsedTagsDataProvider()
    {
        return array(
            array(
                PaymentMethodCode::CARD,
                array(
                    PaymentMethodTag::PRE_AUTHORIZATION,
                ),
                false,
                true,
            ),
            array(
                PaymentMethodCode::CARD,
                array(
                    PaymentMethodTag::RECURRING_PAYMENTS,
                ),
                true,
                false,
            ),
            array(
                PaymentMethodCode::CARD,
                array(
                    PaymentMethodTag::PRE_AUTHORIZATION,
                    PaymentMethodTag::PRE_AUTHORIZATION,
                ),
                false,
                true,
            ),
            array(
                PaymentMethodCode::CARD,
                array(
                    PaymentMethodTag::RECURRING_PAYMENTS,
                    PaymentMethodTag::RECURRING_PAYMENTS,
                ),
                true,
                false,
            ),
            array(
                PaymentMethodCode::CARD,
                array(
                    PaymentMethodTag::PRE_AUTHORIZATION,
                    PaymentMethodTag::RECURRING_PAYMENTS,
                    PaymentMethodTag::PRE_AUTHORIZATION,
                    PaymentMethodTag::RECURRING_PAYMENTS,
                ),
                true,
                true,
            ),
        );
    }

    /**
     * @dataProvider filterDataProvider
     *
     * @param int $result
     * @param bool $isRecurring
     * @param bool $isDeposit
     * @return void
     */
    public function testFiltering(PaymentMethodFilter $filter, $result, $isRecurring = false, $isDeposit = true)
    {
        $methods = $this->client->getActivePaymentMethods($filter, null, $isRecurring, $isDeposit);

        static::assertSame($result, $methods->size());
    }

    /**
     * @return array<array<mixed>>
     */
    public function filterDataProvider()
    {
        return array(
            array(
                new PaymentMethodFilter(
                    array('GBP'),
                    array(),
                    array()
                ),
                1,
            ),
            array(
                new PaymentMethodFilter(
                    array(),
                    array('online'),
                    array()
                ),
                7,
            ),
            array(
                new PaymentMethodFilter(
                    array(),
                    array('online'),
                    array('access_account_owner')
                ),
                2,
            ),
            array(
                new PaymentMethodFilter(
                    array(),
                    array('online'),
                    array('access_account_owner', 'alternative_method')
                ),
                1,
            ),
            array(
                new PaymentMethodFilter(array(), array(), array()),
                1,
                true,
            ),
            array(
                new PaymentMethodFilter(array(), array(), array()),
                1,
                false,
                false,
            ),
        );
    }
}
