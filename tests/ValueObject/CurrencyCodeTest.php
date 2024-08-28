<?php

declare(strict_types=1);

namespace ThePay\ApiClient\Tests\ValueObject;

use ThePay\ApiClient\ValueObject\CurrencyCode;

final class CurrencyCodeTest extends BaseValueObjectTestCase
{
    protected static function getClassName(): string
    {
        return CurrencyCode::class;
    }

    public static function validValueFilteredValueAndStringValueDataProvider(): array
    {
        return [
            ['CZK', 'CZK', 'CZK'],
            ['EUR', 'EUR', 'EUR'],
        ];
    }

    public static function invalidValueAndExceptionMessageDataProvider(): array
    {
        return array_merge(
            NonEmptyStringTest::invalidValueAndExceptionMessageDataProvider(),
            [
                ['AB', 'Value "AB" is not ISO 4217 currency code'],
                ['ABCD', 'Value "ABCD" is not ISO 4217 currency code'],
                ['czk', 'Value "czk" is not ISO 4217 currency code'],
            ],
        );
    }
}
