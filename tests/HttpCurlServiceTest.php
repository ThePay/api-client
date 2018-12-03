<?php

namespace ThePay\ApiClient\Tests;

use PHPUnit\Framework\TestCase;
use ThePay\ApiClient\Http\HttpCurlService;
use ThePay\ApiClient\Service\SignatureService;
use ThePay\ApiClient\TheConfig;

class HttpCurlServiceTest extends TestCase
{
    /** @var HttpCurlService */
    private $httpCurlService;

    public function setUp()
    {
        parent::setUp();
        /** @var TheConfig $config */
        $config = \Mockery::mock('ThePay\ApiClient\TheConfig')->makePartial();
        $signatureService = new SignatureService($config);
        $this->httpCurlService = new HttpCurlService($signatureService);
    }

    public function testGet()
    {
        $response = $this->httpCurlService->get('https://www.thepay.cz');
        static::assertEquals('ThePay\ApiClient\Http\HttpResponse', get_class($response));
    }
}
