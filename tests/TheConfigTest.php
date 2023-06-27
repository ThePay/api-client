<?php

declare(strict_types=1);

namespace ThePay\ApiClient\Tests;

use ThePay\ApiClient\TheConfig;

final class TheConfigTest extends BaseTestCase
{
    /**
     * @dataProvider configDataProvider
     */
    public function testGetGateUrl(TheConfig $config, string $expectedGateUrl, string $expectedApiUrl): void
    {
        self::assertSame($expectedGateUrl, $config->getGateUrl());
        self::assertSame($expectedApiUrl, $config->getApiUrl());
    }

    /**
     * @return array<array<mixed>>
     */
    public static function configDataProvider(): array
    {
        return [
            [new TheConfig(self::MERCHANT_ID, 1, 'pass', 'https://test.api.cz/', 'https://test.gate.cz/'), 'https://test.gate.cz/', 'https://test.api.cz/v1/'],
        ];
    }

    public function testLanguageInConfig(): void
    {
        self::assertSame('cs', $this->config->getLanguage()->getValue());

        $this->config->setLanguage('en');
        self::assertSame('en', $this->config->getLanguage()->getValue());
    }

    public function testInvalidLanguageCodeInConfig(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Value `wtf` is not valid ISO 6391 language code');

        $this->config->setLanguage('wtf');
    }
}
