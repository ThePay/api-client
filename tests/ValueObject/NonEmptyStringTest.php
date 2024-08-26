<?php

declare(strict_types=1);

namespace ThePay\ApiClient\Tests\ValueObject;

use ThePay\ApiClient\ValueObject\NonEmptyString;

final class NonEmptyStringTest extends BaseValueObjectTestCase
{
    protected static function getClassName(): string
    {
        return NonEmptyString::class;
    }

    public static function validValuesAndStringRepresentationsDataProvider(): array
    {
        return [
            ['non-empty string', 'non-empty string'],
        ];
    }

    public static function invalidValuesAndExceptionMessagesDataProvider(): array
    {
        return array_merge(
            StringValueTest::invalidValuesAndExceptionMessagesDataProvider(),
            [
                ['', 'Value "" is not non-empty string'],
                [' ', 'Value " " is not non-empty string'],
            ],
        );
    }
}
