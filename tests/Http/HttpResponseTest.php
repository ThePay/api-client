<?php

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
     * @param string|null $expectedValue
     * @param non-empty-string $headerKey
     * @param array<string, string>|null $headers
     *
     * @return void
     */
    public function testGetHeader($expectedValue, $headerKey, array $headers = null)
    {
        $response = new HttpResponse(null, null, '', $headers);
        self::assertSame($expectedValue, $response->getHeader($headerKey));
    }

    /**
     * @return non-empty-array<array{string|null, non-empty-string, array<string, string>|null}>
     */
    public function dataProviderGetHeader()
    {
        return array(
            array(null, 'no-headers', null),
            array(null, 'empty-headers', array()),
            array(null, 'key-not-found', array('another-key' => 'another-value')),
            array('value-for-lower-key', 'lower-key', array('Lower-Key' => 'value-for-lower-key')),
            array('value-for-upper-key', 'UPPER-KEY', array('Upper-Key' => 'value-for-upper-key')),
            array('value-for-same-key', 'same-key', array('same-key' => 'value-for-same-key')),
        );
    }
}
