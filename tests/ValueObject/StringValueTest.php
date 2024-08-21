<?php

declare(strict_types=1);

namespace ThePay\ApiClient\Tests\ValueObject;

use ThePay\ApiClient\ValueObject\StringValue;

final class StringValueTest extends BaseValueObjectTestCase
{
    protected static function getClassName(): string
    {
        return StringValue::class;
    }

    public static function validValuesAndStringRepresentationsDataProvider(): array
    {
        return [
            ['string', 'string'],
        ];
    }

    public static function invalidValuesAndExceptionMessagesDataProvider(): array
    {
        return [
            [null, 'Value is not string'],
        ];
    }
}
