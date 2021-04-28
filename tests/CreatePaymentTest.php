<?php

namespace ThePay\ApiClient\Tests;

use Mockery;
use ThePay\ApiClient\Model\Address;
use ThePay\ApiClient\Model\CreatePaymentCustomer;
use ThePay\ApiClient\Model\CreatePaymentParams;
use ThePay\ApiClient\Tests\Mocks\Service\ApiMockService;
use ThePay\ApiClient\TheClient;

class CreatePaymentTest extends BaseTestCase
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


    /**
     * @dataProvider createButtonProvider
     *
     * @param string $data
     * @param string $signature
     */
    public function testCreateButton(CreatePaymentParams $params, $data, $signature)
    {
        $r = $this->client->getPaymentButton($params);

        static::assertContains($data, $r);
        static::assertContains($signature, $r);
    }

    public function createButtonProvider()
    {
        return array(
            array(
                new CreatePaymentParams(100, 'CZK', '202001010001'),
                'eyJhbW91bnQiOjEwMCwiY3VycmVuY3lfY29kZSI6IkNaSyIsInVpZCI6IjIwMjAwMTAxMDAwMSIsImxhbmd1YWdlX2NvZGUiOiJjcyIsImlzX3JlY3VycmluZyI6ZmFsc2UsImlzX2RlcG9zaXQiOnRydWUsImNhbl9jdXN0b21lcl9jaGFuZ2VfbWV0aG9kIjp0cnVlLCJtZXJjaGFudF9pZCI6Ijg2YTNlZWQwLTk1YTQtMTFlYS1hYzlmLTM3MWYzNDg4ZTBmYSIsInByb2plY3RfaWQiOjF9',
                'b84277841226f0276ff1f39587555ca3ce2439f71cfdf880b41692f4a0f59ca6',
            ),
            array(
                new CreatePaymentParams(100, 'EUR', '202001010002'),
                'eyJhbW91bnQiOjEwMCwiY3VycmVuY3lfY29kZSI6IkVVUiIsInVpZCI6IjIwMjAwMTAxMDAwMiIsImxhbmd1YWdlX2NvZGUiOiJjcyIsImlzX3JlY3VycmluZyI6ZmFsc2UsImlzX2RlcG9zaXQiOnRydWUsImNhbl9jdXN0b21lcl9jaGFuZ2VfbWV0aG9kIjp0cnVlLCJtZXJjaGFudF9pZCI6Ijg2YTNlZWQwLTk1YTQtMTFlYS1hYzlmLTM3MWYzNDg4ZTBmYSIsInByb2plY3RfaWQiOjF9',
                'e58fcfb7056dd74974ff8656873d9867762ab420176c0a6db5bfbbdbc22b1500',
            ),
        );
    }

    public function testCreateCustomButton()
    {
        $r = $this->client->getPaymentButton(new CreatePaymentParams(100, 'CZK', '202001010003'));
        static::assertContains('Pay!', $r);
        static::assertContains('class="tp-btn"', $r);
        static::assertNotContains('data-payment-method', $r);
        $r = $this->client->getPaymentButton(new CreatePaymentParams(100, 'CZK', '202001010004'), 'Zaplatit!', true, 'bitcoin', array('class' => 'btn btn-success'));
        static::assertContains('Zaplatit!', $r);
        static::assertContains('class="tp-btn btn btn-success"', $r);
        static::assertContains('data-payment-method="bitcoin"', $r);
    }

    public function testGetPaymentMethods()
    {
        $result = $this->client->getPaymentButtons(new CreatePaymentParams(100, 'CZK', '202001010005'));

        static::assertTrue(is_string($result));

        // In default we need to join assets
        static::assertContains('<style', $result);
        static::assertContains('<script', $result);

        // In default we want to send data through form post method, so we need form element
        static::assertContains('<form ', $result);


        // todo: complete test, implementation was not final at this moment
    }

    public function testGetInlineStyles()
    {
        $result = $this->client->getInlineStyles();
        $lines = count(explode("\n", $result));
        // Minified style does not have more than 5 lines.
        static::assertLessThanOrEqual(5, $lines, 'TheClient::getInlineStyles() has more than 5 lines. Fix this bug with "npm run production"');
    }

    public function testGetInlineScripts()
    {
        $result = $this->client->getInlineScripts();
        $lines = count(explode("\n", $result));
        // Minified javascript does not have more than 5 lines.
        static::assertLessThanOrEqual(5, $lines, 'TheClient::getInlineScript() has more than 5 lines. Fix this bug with "npm run production"');
    }

    public function testCreateApiPayment()
    {
        // Create entity with information about customer
        $customer = new CreatePaymentCustomer(
            'Mike',
            'Smith',
            'mike.smith@example.com',
            // Phone number in international format max 15 numeric chars https://en.wikipedia.org/wiki/MSISDN
            '420589687963',
            // Create billing address
            new Address('CZ', 'Prague', '123 00', 'Downstreet 5')
        );

        // Create payment (105.20 € with unique id uid123)
        $createPayment = new CreatePaymentParams(100, 'CZK', 'uid123');
        $createPayment->setOrderId('15478');
        $createPayment->setDescriptionForCustomer('Payment for items on example.com');
        $createPayment->setDescriptionForMerchant('Payment from VIP customer XYZ');
        $createPayment->setCustomer($customer);

        $result = $this->client->createPayment($createPayment);

        static::assertTrue(get_class($result) === 'ThePay\ApiClient\Model\CreatePaymentResponse');
    }
}
