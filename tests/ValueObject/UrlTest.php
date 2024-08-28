<?php

declare(strict_types=1);

namespace ThePay\ApiClient\Tests\ValueObject;

use ThePay\ApiClient\ValueObject\Url;

final class UrlTest extends BaseValueObjectTestCase
{
    protected static function getClassName(): string
    {
        return Url::class;
    }

    public static function validValueFilteredValueAndStringValueDataProvider(): array
    {
        return array_merge(
            [
                ['http://example.com', 'http://example.com', 'http://example.com'],
                ['http://www.example.com', 'http://www.example.com', 'http://www.example.com'],
            ],
            SecureUrlTest::validValueFilteredValueAndStringValueDataProvider(),
        );
    }

    public static function invalidValueAndExceptionMessageDataProvider(): array
    {
        return array_merge(
            NonEmptyStringTest::invalidValueAndExceptionMessageDataProvider(),
            [
                ['example', 'Value "example" is not URL'],
                ['example.com', 'Value "example.com" is not URL'],
                ['www.example.com', 'Value "www.example.com" is not URL'],
            ],
        );
    }
}
