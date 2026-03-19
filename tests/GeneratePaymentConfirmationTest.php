<?php

declare(strict_types=1);

namespace ThePay\ApiClient\Tests;

final class GeneratePaymentConfirmationTest extends BaseTestCase
{
    public function testSuccess(): void
    {
        $theClient = $this->getMockClient();

        $pdfContent = $theClient->generatePaymentConfirmationPdf('testUID', 'cs');

        self::assertTrue(is_string($pdfContent));
    }

    public function testFailed(): void
    {
        $theClient = $this->getMockClient();

        $this->expectException(\InvalidArgumentException::class);

        /** @phpstan-ignore-next-line */
        $theClient->generatePaymentConfirmationPdf('');
    }
}
