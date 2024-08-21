<?php

declare(strict_types=1);

namespace ThePay\ApiClient\Tests\ValueObject;

use ThePay\ApiClient\ValueObject\LanguageCode;

final class LanguageCodeTest extends BaseValueObjectTestCase
{
    protected static function getClassName(): string
    {
        return LanguageCode::class;
    }

    public static function validValueFilteredValueAndStringValueDataProvider(): array
    {
        return [
            ['cs', 'cs', 'cs'],
            ['sk', 'sk', 'sk'],
        ];
    }

    public static function invalidValueAndExceptionMessageDataProvider(): array
    {
        return array_merge(
            NonEmptyStringTest::invalidValueAndExceptionMessageDataProvider(),
            [
                ['a', 'Value "a" is not ISO 6391 language code'],
                ['abc', 'Value "abc" is not ISO 6391 language code'],
                ['CS', 'Value "CS" is not ISO 6391 language code'],
            ],
        );
    }
}
