<?php

namespace ThePay\ApiClient\Tests;

use PHPUnit\Framework\TestCase;
use ThePay\ApiClient\ValueObject\Amount;
use ThePay\ApiClient\ValueObject\CurrencyCode;
use ThePay\ApiClient\ValueObject\Identifier;
use ThePay\ApiClient\ValueObject\LanguageCode;
use ThePay\ApiClient\ValueObject\PaymentMethodCode;
use ThePay\ApiClient\ValueObject\Url;

class ValueObjectTest extends TestCase
{
    /**
     * @dataProvider amountProvider
     *
     * @param int $amount
     * @return void
     */
    public function testAmount($amount)
    {
        $a = Amount::create($amount);
        $b = new Amount($amount);

        static::assertTrue($a->equals($b));
        static::assertTrue($b->equals($a));
        static::assertSame($amount, $a->getValue());
        static::assertSame((string) $amount, (string) $a);
    }

    /**
     * @dataProvider invalidAmountProvider
     *
     * @param mixed $amount
     * @return void
     */
    public function testAmountInvalidValues($amount)
    {
        $this->setExpectedException('InvalidArgumentException', 'Value has to be an integer.');
        Amount::create($amount);
    }

    /**
     * @dataProvider currencyCodeProvider
     *
     * @param string $currency
     * @return void
     */
    public function testCurrencyCode($currency)
    {
        $a = CurrencyCode::create($currency);
        $b = new CurrencyCode($currency);

        static::assertTrue($a->equals($b));
        static::assertTrue($b->equals($a));
        static::assertSame($currency, $a->getValue());
        static::assertSame((string) $currency, (string) $a);
    }

    /**
     * @dataProvider invalidCurrencyCodeProvider
     *
     * @param mixed $currency
     * @return void
     */
    public function testCurrencyCodeInvalidValues($currency)
    {
        $this->setExpectedException('InvalidArgumentException', 'Value `' . $currency . '` is not valid ISO 4217 currency code');
        CurrencyCode::create($currency);
    }

    /**
     * @dataProvider identifierProvider
     *
     * @param string $id
     * @return void
     */
    public function testIdentifier($id)
    {
        $a = Identifier::create($id);
        $b = new Identifier($id);

        static::assertTrue($a->equals($b));
        static::assertTrue($b->equals($a));
        static::assertSame((string) $id, $a->getValue());
        static::assertSame((string) $id, (string) $a);
    }

    /**
     * @dataProvider invalidIdentifierProvider
     *
     * @param string $id
     * @return void
     */
    public function testIdentifierInvalidValues($id)
    {
        $this->setExpectedException('InvalidArgumentException', 'Value\'s length has to be up to 100 characters');
        Identifier::create($id);
    }

    /**
     * @dataProvider langCodeProvider
     *
     * @param string $code
     * @return void
     */
    public function testLanguageCode($code)
    {
        $a = LanguageCode::create($code);
        $b = new LanguageCode($code);

        static::assertTrue($a->equals($b));
        static::assertTrue($b->equals($a));
        static::assertSame($code, $a->getValue());
        static::assertSame((string) $code, (string) $a);
    }

    /**
     * @dataProvider invalidLangCodeProvider
     *
     * @param mixed $code
     * @return void
     */
    public function testLanguageCodeInvalidValues($code)
    {
        $this->setExpectedException('InvalidArgumentException', 'Value `' . $code . '` is not valid ISO 6391 language code');
        LanguageCode::create($code);
    }

    /**
     * @dataProvider urlProvider
     *
     * @param string $url
     * @return void
     */
    public function testUrl($url)
    {
        $a = Url::create($url);
        $b = new Url($url);

        static::assertTrue($a->equals($b));
        static::assertTrue($b->equals($a));
        static::assertSame($url, $a->getValue());
        static::assertSame((string) $url, (string) $a);
    }

    /**
     * @dataProvider invalidUrlProvider
     *
     * @param mixed $url
     * @return void
     */
    public function testUrlInvalidValues($url)
    {
        $this->setExpectedException('InvalidArgumentException', 'Url is in incorrect format');
        Url::create($url);
    }

    /**
     * @param string|null $expectedException Exception class name if is expected.
     * @param string $code
     * @return void
     *
     * @dataProvider paymentMethodCodeProvider
     */
    public function testPaymentMethodCode($expectedException, $code)
    {
        if ($expectedException) {
            self::setExpectedException($expectedException);
        }

        $value = new PaymentMethodCode($code);

        self::assertSame($code, $value->getValue());
    }

    /**
     * @return array<array<int>>
     */
    public function amountProvider()
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
    public function invalidAmountProvider()
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
    public function currencyCodeProvider()
    {
        return [
            ['CZK'],
            ['USD'],
        ];
    }

    /**
     * @return array<array<mixed>>
     */
    public function invalidCurrencyCodeProvider()
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
    public function identifierProvider()
    {
        return [
            ['1'],
            [1],
            [$this->randomChars(50)],
            [$this->randomChars(100)],
        ];
    }

    /**
     * @return array<array<mixed>>
     */
    public function invalidIdentifierProvider()
    {
        return [
            [$this->randomChars(101)],
        ];
    }

    /**
     * @return array<array<non-empty-string>>
     */
    public function langCodeProvider()
    {
        return [
            ['cs'],
            ['en'],
        ];
    }

    /**
     * @return array<array<mixed>>
     */
    public function invalidLangCodeProvider()
    {
        return [
            ['csc'],
            [1],
        ];
    }

    /**
     * @return array<array<non-empty-string>>
     */
    public function urlProvider()
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
    public function invalidUrlProvider()
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
     * @return array<array<mixed>>
     */
    public function paymentMethodCodeProvider()
    {
        // [$expectedException, $code]
        return [
            [null, PaymentMethodCode::CARD],
            [null, 'not-existing-payment-method'],
        ];
    }

    /**
     * @param positive-int $length
     * @return non-empty-string
     */
    private function randomChars($length)
    {
        /** @var non-empty-string $result */
        $result = '';
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_-';

        for ($i = 0; $i < $length; $i++) {
            $result .= substr($characters, rand(0, strlen($characters) - 1), 1);
        }

        return $result;
    }
}
