<?php

declare(strict_types=1);

namespace ThePay\ApiClient\Tests\ValueObject;

use ThePay\ApiClient\ValueObject\PhoneNumber;

final class PhoneNumberTest extends BaseValueObjectTestCase
{
    protected static function getClassName(): string
    {
        return PhoneNumber::class;
    }

    public static function validValueFilteredValueAndStringValueDataProvider(): array
    {
        return [
            ['+111 222 333 444', '111222333444', '111222333444'],
            ['00111 222 333 444', '111222333444', '111222333444'],
            ['222 333 444', '222333444', '222333444'],
            ['222333444', '222333444', '222333444'],
        ];
    }

    public static function invalidValueAndExceptionMessageDataProvider(): array
    {
        return array_merge(
            NonEmptyStringTest::invalidValueAndExceptionMessageDataProvider(),
            [
                ['string', 'Value "string" is not phone number in MSISDN format'],
            ],
        );
    }
}
