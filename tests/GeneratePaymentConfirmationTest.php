<?php

namespace ThePay\ApiClient\Tests;

final class GeneratePaymentConfirmationTest extends BaseTestCase
{
    /**
     * @return void
     */
    public function testSuccess()
    {
        $theClient = $this->getMockClient();

        $pdfContent = $theClient->generatePaymentConfirmationPdf('testUID', 'cs');

        self::assertTrue(is_string($pdfContent));

        \Mockery::close();
    }

    /**
     * @dataProvider dataFailed
     *
     * @param class-string<\Exception> $expectedException
     *
     * @return void
     */
    public function testFailed($expectedException)
    {
        $theClient = $this->getMockClient();

        try {
            /** @phpstan-ignore-next-line */
            $theClient->generatePaymentConfirmationPdf('');
        } catch (\Exception $exception) {
            self::assertSame($expectedException, get_class($exception));
        }
    }

    /**
     * @return array<array<mixed>>
     */
    public function dataFailed()
    {
        return array(
            // failed input
            array('InvalidArgumentException'),
        );
    }
}
