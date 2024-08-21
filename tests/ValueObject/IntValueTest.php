<?php

declare(strict_types=1);

namespace ThePay\ApiClient\Tests\ValueObject;

use ThePay\ApiClient\ValueObject\IntValue;

final class IntValueTest extends BaseValueObjectTestCase
{
    protected static function getClassName(): string
    {
        return IntValue::class;
    }

    public static function validValueFilteredValueAndStringValueDataProvider(): array
    {
        return [
            [15, 15, '15'],
            [0xf, 15, '15'],
            [15.0, 15, '15'],
            ['15', 15, '15'],
        ];
    }

    public static function invalidValueAndExceptionMessageDataProvider(): array
    {
        return [
            [null, 'Value is not int'],
            [15.1, 'Value is not int'],
            ['15.0', 'Value is not int'],
            ['A1', 'Value is not int'],
            ['1B', 'Value is not int'],
        ];
    }
}
