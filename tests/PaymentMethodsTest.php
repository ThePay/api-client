<?php

namespace ThePay\ApiClient\Tests;

use Mockery;
use ThePay\ApiClient\Filter\PaymentMethodFilter;
use ThePay\ApiClient\Tests\Mocks\Service\ApiMockService;
use ThePay\ApiClient\TheClient;

class PaymentMethodsTest extends BaseTestCase
{
    /** @var TheClient */
    private $client;

    protected function setUp()
    {
        parent::setUp();

        $httpService = Mockery::mock('ThePay\ApiClient\Http\HttpServiceInterface');
        $apiService = new ApiMockService($this->config, $httpService);

        $this->client = new TheClient($this->config, null, $httpService, $apiService);
    }

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
     * @dataProvider filterDataProvider
     *
     * @param int $result
     */
    public function testFiltering(PaymentMethodFilter $filter, $result)
    {
        $methods = $this->client->getActivePaymentMethods($filter);

        static::assertSame($result, $methods->size());
    }

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
        );
    }
}
