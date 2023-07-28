<?php

namespace ThePay\ApiClient\Tests;

use ThePay\ApiClient\Http\HttpResponse;
use ThePay\ApiClient\Http\HttpServiceInterface;
use ThePay\ApiClient\TheClient;

final class GeneratePaymentConfirmationTest extends BaseTestCase
{
    /**
     * @return void
     */
    public function testSuccess()
    {
        /** @var HttpServiceInterface $httpService */
        $httpService = \Mockery::mock('ThePay\ApiClient\Http\HttpServiceInterface');

        call_user_func(array($httpService, 'shouldReceive'), 'get')->once()
            ->with($this->config->getApiUrl() . 'projects/1/payments/testUID/generate_confirmation?language=cs&merchant_id=' . self::MERCHANT_ID)
            ->andReturn(
                new HttpResponse(
                    null,
                    200,
                    '',
                    null,
                    'test pdf content'
                )
            );

        $theClient = new TheClient(
            $this->config,
            null,
            $httpService
        );

        $pdfContent = $theClient->generatePaymentConfirmationPdf('testUID', 'cs');

        self::assertSame('test pdf content', $pdfContent);

        \Mockery::close();
    }

    /**
     * @dataProvider dataFailed
     *
     * @param class-string<\Exception> $expectedException
     *
     * @return void
     */
    public function testFailed($expectedException, HttpResponse $response)
    {
        /** @var HttpServiceInterface $httpService */
        $httpService = \Mockery::mock('ThePay\ApiClient\Http\HttpServiceInterface');
        call_user_func(array($httpService, 'shouldReceive'), 'get')->once()
            ->andReturn($response);

        $theClient = new TheClient(
            $this->config,
            null,
            $httpService
        );

        try {
            $theClient->generatePaymentConfirmationPdf('testUID');
        } catch (\Exception $exception) {
            self::assertSame($expectedException, get_class($exception));
        }

        \Mockery::close();
    }

    /**
     * @return array<array<mixed>>
     */
    public function dataFailed()
    {
        return array(
            // failed response code
            array('RuntimeException', new HttpResponse(null, 400)),
            // response without body
            array('ThePay\ApiClient\Exception\ApiException', new HttpResponse(null, 200)),
        );
    }
}
