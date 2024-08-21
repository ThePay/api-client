<?php

declare(strict_types=1);

namespace ThePay\ApiClient\Tests\ValueObject;

use ThePay\ApiClient\ValueObject\CountryCode;

final class CountryCodeTest extends BaseValueObjectTestCase
{
    protected static function getClassName(): string
    {
        return CountryCode::class;
    }

    public static function validValueFilteredValueAndStringValueDataProvider(): array
    {
        return [
            ['CZ', 'CZ', 'CZ'],
            ['SK', 'SK', 'SK'],
        ];
    }

    public static function invalidValueAndExceptionMessageDataProvider(): array
    {
        return array_merge(
            NonEmptyStringTest::invalidValueAndExceptionMessageDataProvider(),
            [
                ['A', 'Value "A" is not ISO 3166-1 (alpha-2) country code'],
                ['ABC', 'Value "ABC" is not ISO 3166-1 (alpha-2) country code'],
                ['cz', 'Value "cz" is not ISO 3166-1 (alpha-2) country code'],
            ],
        );
    }
}
