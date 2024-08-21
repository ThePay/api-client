<?php

declare(strict_types=1);

namespace ThePay\ApiClient\Tests\ValueObject;

use ThePay\ApiClient\ValueObject\Identifier;

final class IdentifierTest extends BaseValueObjectTestCase
{
    protected static function getClassName(): string
    {
        return Identifier::class;
    }

    public static function validValueFilteredValueAndStringValueDataProvider(): array
    {
        $identifier = str_repeat('i', Identifier::STRLEN_MAX);
        return [
            ['i', 'i', 'i'],
            [$identifier, $identifier, $identifier],
        ];
    }

    public static function invalidValueAndExceptionMessageDataProvider(): array
    {
        $tooLongString = str_repeat('i', Identifier::STRLEN_MAX + 1);
        return array_merge(
            NonEmptyStringTest::invalidValueAndExceptionMessageDataProvider(),
            [
                [$tooLongString, sprintf('Value "%s" is not up to %d characters', $tooLongString, Identifier::STRLEN_MAX)],
            ],
        );
    }
}
