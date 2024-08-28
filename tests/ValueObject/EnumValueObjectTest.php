<?php

declare(strict_types=1);

namespace ThePay\ApiClient\Tests\ValueObject;

final class EnumValueObjectTest extends BaseValueObjectTestCase
{
    protected static function getClassName(): string
    {
        return TestedEnumValueObject::class;
    }

    public static function validValueFilteredValueAndStringValueDataProvider(): array
    {
        return [
            [TestedEnumValueObject::CASE, 'case', 'case'],
        ];
    }

    public static function invalidValueAndExceptionMessageDataProvider(): array
    {
        return array_merge(
            StringValueTest::invalidValueAndExceptionMessageDataProvider(),
            [
                ['not case', 'Value "not case" is not case'],
            ],
        );
    }
}
