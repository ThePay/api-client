<?php

declare(strict_types=1);

namespace ThePay\ApiClient\Tests\ValueObject;

use ThePay\ApiClient\ValueObject\Amount;

final class AmountTest extends BaseValueObjectTestCase
{
    protected static function getClassName(): string
    {
        return Amount::class;
    }

    public static function validValueFilteredValueAndStringValueDataProvider(): array
    {
        return IntValueTest::validValueFilteredValueAndStringValueDataProvider();
    }

    public static function invalidValueAndExceptionMessageDataProvider(): array
    {
        return IntValueTest::invalidValueAndExceptionMessageDataProvider();
    }
}
