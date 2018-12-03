<?php

namespace ThePay\ApiClient\Tests;

use PHPUnit\Framework\TestCase;
use ThePay\ApiClient\Service\SignatureService;
use ThePay\ApiClient\TheConfig;

class SignatureServiceTest extends TestCase
{
    /**
     * @dataProvider signatureForApiDataProvider
     */
    public function testGetSignatureForApi(TheConfig $config)
    {
        $service = new SignatureService($config);

        $signature = $service->getSignatureForApi();

        static::assertInstanceOf('ThePay\ApiClient\Model\ApiSignature', $signature);

        static::assertNotNull($signature->getDate());

        $date = new \DateTime($signature->getDate());
        static::assertInstanceOf('DateTime', $date);
        static::assertSame($signature->getDate(), $date->format(SignatureService::FORMAT_RFC7231));
        static::assertNotNull($signature->getHash());
        static::assertSame(
            $signature->getHash(),
            hash('sha256', $config->getMerchantId().$config->getPassword().$signature->getDate())
        );
        static::assertSame(64, strlen($signature->getHash())); // length of sha256 hash should always be 64 characters
    }

    public function signatureForApiDataProvider()
    {
        return array(
            array(new TheConfig(BaseTestCase::MERCHANT_ID, 1, 'password', 'https://test.api.cz/', 'https://test.gate.cz/')),
            array(new TheConfig('86a3eed0-95a4-11ea-ac9f-371f3488e0f2', 1, 'anotherPassword', 'https://test.api.cz/', 'https://test.gate.cz/')),
            array(new TheConfig('86a3eed0-95a4-11ea-ac9f-371f3488e0f3', 35, 'superLongAndDifficultToRememberPasswordThatWeDontUseOnProductionForOurAccounts', 'https://test.api.cz/', 'https://test.gate.cz/')),
        );
    }
}
