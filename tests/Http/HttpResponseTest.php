<?php

declare(strict_types=1);

namespace ThePay\ApiClient\Tests\Http;

use PHPUnit\Framework\TestCase;
use ThePay\ApiClient\Http\HttpResponse;

/**
 * @covers \ThePay\ApiClient\Http\HttpResponse
 */
final class HttpResponseTest extends TestCase
{
    /**
     * @dataProvider dataProviderGetHeader
     *
     * @param non-empty-string $headerKey
     * @param array<string, string>|null $headers
     */
    public function testGetHeader(?string $expectedValue, string $headerKey, array $headers = null): void
    {
        $response = new HttpResponse(null, null, '', $headers);
        self::assertSame($expectedValue, $response->getHeader($headerKey));
    }

    /**
     * @return non-empty-array<array{string|null, non-empty-string, array<string, string>|null}>
     */
    public static function dataProviderGetHeader(): array
    {
        return [
            [null, 'no-headers', null],
            [null, 'empty-headers', []],
            [null, 'key-not-found', ['another-key' => 'another-value']],
            ['value-for-lower-key', 'lower-key', ['Lower-Key' => 'value-for-lower-key']],
            ['value-for-upper-key', 'UPPER-KEY', ['Upper-Key' => 'value-for-upper-key']],
            ['value-for-same-key', 'same-key', ['same-key' => 'value-for-same-key']],
        ];
    }
}
