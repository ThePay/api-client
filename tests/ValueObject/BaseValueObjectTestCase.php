<?php

declare(strict_types=1);

namespace ThePay\ApiClient\Tests\ValueObject;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use ThePay\ApiClient\ValueObject\BaseValueObject;

abstract class BaseValueObjectTestCase extends TestCase
{
    /**
     * @dataProvider validValuesAndStringRepresentationsDataProvider
     *
     * @param mixed $value
     */
    public function testCreatesWorkingInstanceWithValidValue($value, string $stringRepresentation): void
    {
        $className = static::getClassName();
        $a = $className::create($value);
        $b = new $className($value);

        self::assertTrue($a->equals($b));
        self::assertTrue($b->equals($a));
        self::assertSame($value, $a->getValue());
        self::assertSame($stringRepresentation, (string) $a);
    }

    /**
     * @dataProvider invalidValuesAndExceptionMessagesDataProvider
     *
     * @param mixed $value
     */
    public function testThrowsWithInvalidValue($value, string $exceptionMessage): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage($exceptionMessage);

        $className = static::getClassName();
        $className::create($value);
    }

    /**
     * @return class-string<BaseValueObject<mixed>>
     */
    abstract protected static function getClassName(): string;

    /**
     * @return array<array<mixed|string>>|array<string, array<mixed|string>>
     */
    abstract public static function validValuesAndStringRepresentationsDataProvider(): array;

    /**
     * @return array<array<mixed|string>>|array<string, array<mixed|string>>
     */
    abstract public static function invalidValuesAndExceptionMessagesDataProvider(): array;
}
