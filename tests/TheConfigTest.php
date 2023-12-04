<?php

namespace ThePay\ApiClient\Tests;

use ThePay\ApiClient\TheConfig;

class TheConfigTest extends BaseTestCase
{
    /**
     * @dataProvider configDataProvider
     *
     * @param string $expectedGateUrl
     * @param string $expectedApiUrl
     * @return void
     */
    public function testGetGateUrl(TheConfig $config, $expectedGateUrl, $expectedApiUrl)
    {
        static::assertSame($expectedGateUrl, $config->getGateUrl());
        static::assertSame($expectedApiUrl, $config->getApiUrl());
    }

    /**
     * @return array<array<mixed>>
     */
    public function configDataProvider()
    {
        return array(
            array(new TheConfig(self::MERCHANT_ID, 1, 'pass', 'https://test.api.cz/', 'https://test.gate.cz/'), 'https://test.gate.cz/', 'https://test.api.cz/v1/'),
        );
    }

    /**
     * @return void
     */
    public function testLanguageInConfig()
    {
        static::assertSame('cs', $this->config->getLanguage()->getValue());

        $this->config->setLanguage('en');
        static::assertSame('en', $this->config->getLanguage()->getValue());
    }

    /**
     * @return void
     */
    public function testInvalidLanguageCodeInConfig()
    {
        $this->setExpectedException('InvalidArgumentException', 'Value `wtf` is not valid ISO 6391 language code');
        $this->config->setLanguage('wtf');
    }
}
