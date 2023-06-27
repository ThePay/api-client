<?php

declare(strict_types=1);

namespace ThePay\ApiClient\Tests;

use PHPUnit\Framework\TestCase;
use ThePay\ApiClient\ValueObject\Amount;
use ThePay\ApiClient\ValueObject\CurrencyCode;
use ThePay\ApiClient\ValueObject\Identifier;
use ThePay\ApiClient\ValueObject\LanguageCode;
use ThePay\ApiClient\ValueObject\Url;

final class ValueObjectTest extends TestCase
{
    /**
     * @dataProvider amountProvider
     */
    public function testAmount(int $amount): void
    {
        $a = Amount::create($amount);
        $b = new Amount($amount);

        self::assertTrue($a->equals($b));
        self::assertTrue($b->equals($a));
        self::assertSame($amount, $a->getValue());
        self::assertSame((string) $amount, (string) $a);
    }

    /**
     * @dataProvider invalidAmountProvider
     *
     * @param mixed $amount
     */
    public function testAmountInvalidValues($amount): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Value has to be an integer.');

        Amount::create($amount);
    }

    /**
     * @dataProvider currencyCodeProvider
     */
    public function testCurrencyCode(string $currency): void
    {
        $a = CurrencyCode::create($currency);
        $b = new CurrencyCode($currency);

        self::assertTrue($a->equals($b));
        self::assertTrue($b->equals($a));
        self::assertSame($currency, $a->getValue());
        self::assertSame($currency, (string) $a);
    }

    /**
     * @dataProvider invalidCurrencyCodeProvider
     *
     * @param mixed $currency
     */
    public function testCurrencyCodeInvalidValues($currency): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Value `' . $currency . '` is not valid ISO 4217 currency code');

        CurrencyCode::create($currency);
    }

    /**
     * @dataProvider identifierProvider
     *
     * @param mixed $id
     */
    public function testIdentifier($id): void
    {
        $a = Identifier::create($id);
        $b = new Identifier($id);

        self::assertTrue($a->equals($b));
        self::assertTrue($b->equals($a));
        self::assertSame((string) $id, $a->getValue());
        self::assertSame((string) $id, (string) $a);
    }

    public function testIdentifierInvalidValues(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Value\'s length has to be up to 100 characters');

        Identifier::create(self::randomChars(101));
    }

    /**
     * @dataProvider langCodeProvider
     */
    public function testLanguageCode(string $code): void
    {
        $a = LanguageCode::create($code);
        $b = new LanguageCode($code);

        self::assertTrue($a->equals($b));
        self::assertTrue($b->equals($a));
        self::assertSame($code, $a->getValue());
        self::assertSame($code, (string) $a);
    }

    /**
     * @dataProvider invalidLangCodeProvider
     *
     * @param mixed $code
     */
    public function testLanguageCodeInvalidValues($code): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Value `' . $code . '` is not valid ISO 6391 language code');

        LanguageCode::create($code);
    }

    /**
     * @dataProvider urlProvider
     */
    public function testUrl(string $url): void
    {
        $a = Url::create($url);
        $b = new Url($url);

        self::assertTrue($a->equals($b));
        self::assertTrue($b->equals($a));
        self::assertSame($url, $a->getValue());
        self::assertSame($url, (string) $a);
    }

    /**
     * @dataProvider invalidUrlProvider
     *
     * @param mixed $url
     */
    public function testUrlInvalidValues($url): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Url is in incorrect format');

        Url::create($url);
    }

    /**
     * @return array<array<int>>
     */
    public static function amountProvider(): array
    {
        return [
            [0],
            [1],
            [256],
            [0xf],
        ];
    }

    /**
     * @return array<array<mixed>>
     */
    public static function invalidAmountProvider(): array
    {
        return [
            [0.1],
            ['0xf'],
            ['hello'],
            [true],
            ['256'],
        ];
    }

    /**
     * @return array<array<non-empty-string>>
     */
    public static function currencyCodeProvider(): array
    {
        return [
            ['CZK'],
            ['USD'],
        ];
    }

    /**
     * @return array<array<mixed>>
     */
    public static function invalidCurrencyCodeProvider(): array
    {
        return [
            ['cz'],
            [1],
            ['HELLO'],
        ];
    }

    /**
     * @return array<array<mixed>>
     */
    public static function identifierProvider(): array
    {
        return [
            ['1'],
            [1],
            [self::randomChars(50)],
            [self::randomChars(100)],
        ];
    }

    /**
     * @return array<array<non-empty-string>>
     */
    public static function langCodeProvider(): array
    {
        return [
            ['cs'],
            ['en'],
        ];
    }

    /**
     * @return array<array<mixed>>
     */
    public static function invalidLangCodeProvider(): array
    {
        return [
            ['csc'],
            [1],
        ];
    }

    /**
     * @return array<array<non-empty-string>>
     */
    public static function urlProvider(): array
    {
        return [
            ['http://test.com'],
            ['http://www.test.com'],
            ['https://test.com'],
            ['https://www.test.com'],
        ];
    }

    /**
     * @return array<array<mixed>>
     */
    public static function invalidUrlProvider(): array
    {
        return [
            [1],
            [1.01],
            ['hello'],
            ['test.com'],
            ['www.test.com'],
        ];
    }

    /**
     * @param positive-int $length
     * @return non-empty-string
     */
    private static function randomChars(int $length): string
    {
        $result = '';
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_-';

        for ($i = 0; $i < $length; $i++) {
            $result .= substr($characters, rand(0, strlen($characters) - 1), 1);
        }

        return $result;
    }
}
