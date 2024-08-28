<?php

declare(strict_types=1);

namespace ThePay\ApiClient\Tests\ValueObject;

use ThePay\ApiClient\ValueObject\SecureUrl;

final class SecureUrlTest extends BaseValueObjectTestCase
{
    protected static function getClassName(): string
    {
        return SecureUrl::class;
    }

    public static function validValueFilteredValueAndStringValueDataProvider(): array
    {
        return [
            ['https://example.com', 'https://example.com', 'https://example.com'],
            ['https://www.example.com', 'https://www.example.com', 'https://www.example.com'],
        ];
    }

    public static function invalidValueAndExceptionMessageDataProvider(): array
    {
        return array_merge(
            UrlTest::invalidValueAndExceptionMessageDataProvider(),
            [
                ['http://example.com', 'Value "http://example.com" is not "https://" prefixed URL'],
                ['http://www.example.com', 'Value "http://www.example.com" is not "https://" prefixed URL'],
            ],
        );
    }
}
