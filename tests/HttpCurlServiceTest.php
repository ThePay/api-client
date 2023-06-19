<?php

declare(strict_types=1);

namespace ThePay\ApiClient\Tests;

use PHPUnit\Framework\TestCase;
use ThePay\ApiClient\Http\HttpCurlService;
use ThePay\ApiClient\Http\HttpResponse;
use ThePay\ApiClient\Service\SignatureService;
use ThePay\ApiClient\TheConfig;

final class HttpCurlServiceTest extends TestCase
{
    private HttpCurlService $httpCurlService;

    public function setUp(): void
    {
        parent::setUp();
        $config = $this->createPartialMock(TheConfig::class, []);
        $signatureService = new SignatureService($config);
        $this->httpCurlService = new HttpCurlService($signatureService);
    }

    public function testGet(): void
    {
        $response = $this->httpCurlService->get('https://www.thepay.cz');
        self::assertEquals(HttpResponse::class, get_class($response));
    }
}
