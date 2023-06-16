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
     * @dataProvider createButtonProvider
     *
     * @param string $data
     * @param string $signature
     * @return void
     */
    public function testCreateButton(CreatePaymentParams $params, $data, $signature)
    {
        $r = $this->client->getPaymentButton($params);

        static::assertContains($data, $r);
        static::assertContains($signature, $r);
    }

    /**
     * @return array<array<mixed>>
     */
    public function createButtonProvider()
    {
        return [
            [
                new CreatePaymentParams(100, 'CZK', '202001010001'),
                'eyJhbW91bnQiOjEwMCwiY3VycmVuY3lfY29kZSI6IkNaSyIsInVpZCI6IjIwMjAwMTAxMDAwMSIsImxhbmd1YWdlX2NvZGUiOiJjcyIsImlzX3JlY3VycmluZyI6ZmFsc2UsImlzX2RlcG9zaXQiOnRydWUsInNhdmVfYXV0aG9yaXphdGlvbiI6ZmFsc2UsImNhbl9jdXN0b21lcl9jaGFuZ2VfbWV0aG9kIjp0cnVlLCJtZXJjaGFudF9pZCI6Ijg2YTNlZWQwLTk1YTQtMTFlYS1hYzlmLTM3MWYzNDg4ZTBmYSIsInByb2plY3RfaWQiOjF9',
                '99e8b5f1bb32cd6937af1ebbcc7d3b337b7f5524f244a5d694c930afef1a16a9',
            ],
            [
                new CreatePaymentParams(100, 'EUR', '202001010002'),
                'eyJhbW91bnQiOjEwMCwiY3VycmVuY3lfY29kZSI6IkVVUiIsInVpZCI6IjIwMjAwMTAxMDAwMiIsImxhbmd1YWdlX2NvZGUiOiJjcyIsImlzX3JlY3VycmluZyI6ZmFsc2UsImlzX2RlcG9zaXQiOnRydWUsInNhdmVfYXV0aG9yaXphdGlvbiI6ZmFsc2UsImNhbl9jdXN0b21lcl9jaGFuZ2VfbWV0aG9kIjp0cnVlLCJtZXJjaGFudF9pZCI6Ijg2YTNlZWQwLTk1YTQtMTFlYS1hYzlmLTM3MWYzNDg4ZTBmYSIsInByb2plY3RfaWQiOjF9',
                '03e61b053d5b0ccd17a2913a5b005e35fd499b74472adec92e792eecdd123895',
            ],
        ];
    }

    /**
     * @return void
     */
    public function testCreateCustomButton()
    {
        $r = $this->client->getPaymentButton(new CreatePaymentParams(100, 'CZK', '202001010003'));
        static::assertContains('Pay!', $r);
        static::assertContains('class="tp-btn"', $r);
        static::assertNotContains('data-payment-method', $r);
        $r = $this->client->getPaymentButton(new CreatePaymentParams(100, 'CZK', '202001010004'), 'Zaplatit!', true, 'bitcoin', ['class' => 'btn btn-success']);
        static::assertContains('Zaplatit!', $r);
        static::assertContains('class="tp-btn btn btn-success"', $r);
        static::assertContains('data-payment-method="bitcoin"', $r);
    }

    /**
     * @return void
     */
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

    /**
     * @return void
     */
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
