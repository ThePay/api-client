<?php

declare(strict_types=1);

namespace ThePay\ApiClient\Tests\ValueObject;

use ThePay\ApiClient\ValueObject\EmailAddress;

final class EmailAddressTest extends BaseValueObjectTestCase
{
    protected static function getClassName(): string
    {
        return EmailAddress::class;
    }

    public static function validValuesAndStringRepresentationsDataProvider(): array
    {
        return [
            ['user@example.com', 'user@example.com'],
            ['user+label@example.com', 'user+label@example.com'],
            ['idn@测试假域名.com', 'idn@测试假域名.com'],
        ];
    }

    public static function invalidValuesAndExceptionMessagesDataProvider(): array
    {
        return array_merge(
            NonEmptyStringTest::invalidValuesAndExceptionMessagesDataProvider(),
            [
                ['foo', 'Value "foo" is not e-mail address'],
            ],
        );
    }
}
