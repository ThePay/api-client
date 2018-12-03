<?php


namespace ThePay\ApiClient\Tests;

use PHPUnit\Framework\TestCase;
use ThePay\ApiClient\ValueObject\Amount;
use ThePay\ApiClient\ValueObject\CurrencyCode;
use ThePay\ApiClient\ValueObject\Identifier;
use ThePay\ApiClient\ValueObject\LanguageCode;
use ThePay\ApiClient\ValueObject\Url;

class ValueObjectTest extends TestCase
{
    /**
     * @dataProvider amountProvider
     *
     * @param int $amount
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
     */
    public function testCurrencyCodeInvalidValues($currency)
    {
        $this->setExpectedException('InvalidArgumentException', 'Value `'.$currency.'` is not valid ISO 4217 currency code');
        CurrencyCode::create($currency);
    }

    /**
     * @dataProvider identifierProvider
     *
     * @param string $id
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
     */
    public function testLanguageCodeInvalidValues($code)
    {
        $this->setExpectedException('InvalidArgumentException', 'Value `'.$code.'` is not valid ISO 6391 language code');
        LanguageCode::create($code);
    }

    /**
     * @dataProvider urlProvider
     *
     * @param string $url
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
     */
    public function testUrlInvalidValues($url)
    {
        $this->setExpectedException('InvalidArgumentException', 'Url is in incorrect format');
        Url::create($url);
    }

    public function amountProvider()
    {
        return array(
            array(0),
            array(1),
            array(256),
            array(0xf),
        );
    }

    public function invalidAmountProvider()
    {
        return array(
            array(0.1),
            array('0xf'),
            array('hello'),
            array(true),
            array('256'),
        );
    }

    public function currencyCodeProvider()
    {
        return array(
            array('CZK'),
            array('USD'),
        );
    }

    public function invalidCurrencyCodeProvider()
    {
        return array(
            array('cz'),
            array(1),
            array('HELLO')
        );
    }

    public function identifierProvider()
    {
        return array(
            array('1'),
            array(1),
            array($this->randomChars(50)),
            array($this->randomChars(100)),
        );
    }

    public function invalidIdentifierProvider()
    {
        return array(
            array($this->randomChars(101)),
        );
    }

    public function langCodeProvider()
    {
        return array(
            array('cs'),
            array('en'),
        );
    }

    public function invalidLangCodeProvider()
    {
        return array(
            array('csc'),
            array(1),
        );
    }

    public function urlProvider()
    {
        return array(
            array('http://test.com'),
            array('http://www.test.com'),
            array('https://test.com'),
            array('https://www.test.com'),
        );
    }

    public function invalidUrlProvider()
    {
        return array(
            array(1),
            array(1.01),
            array('hello'),
            array('test.com'),
            array('www.test.com'),
        );
    }

    private function randomChars($length)
    {
        $result = '';
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_-';

        for ($i = 0; $i < $length; $i++) {
            $result .= substr($characters, rand(0, strlen($characters)-1), 1);
        }

        return $result;
    }
}
