<?php

namespace ThePay\ApiClient\Http;

use ThePay\ApiClient\Service\SignatureService;
use ThePay\ApiClient\TheClient;

/**
 * Class HttpCurlService is simple curl wrapper
 */
class HttpCurlService implements HttpServiceInterface
{
    const HEADER_SIGNATURE = 'Signature';
    const HEADER_SIGNATURE_DATE = 'SignatureDate';
    const HEADER_PLATFORM = 'Platform';

    /** @var SignatureService */
    private $signatureService;

    /** @var CurlWrapperFactory */
    private $curlWrapperFactory;

    /**
    * HttpCurlService constructor.
    *
    * @param SignatureService $signatureService
    */
    public function __construct(SignatureService $signatureService)
    {
        $this->signatureService = $signatureService;
        $this->curlWrapperFactory = new CurlWrapperFactory();
    }

    /**
     * @param string $url
     * @return HttpResponse
     */
    public function get($url)
    {
        $curl = $this->curlWrapperFactory->create($this->getSignatureAndVersionHeaders());
        return $curl->request(CurlWrapper::METHOD_GET, $url);
    }

    /**
     * @param string $url
     * @param string $data POST request body content
     * @return HttpResponse
     */
    public function post($url, $data = '')
    {
        $curl = $this->curlWrapperFactory->create($this->getSignatureAndVersionHeaders());
        return $curl->request(CurlWrapper::METHOD_POST, $url, $data);
    }

    /**
     * @param string $url
     * @return HttpResponse
     */
    public function delete($url)
    {
        $curl = $this->curlWrapperFactory->create($this->getSignatureAndVersionHeaders());
        return $curl->request(CurlWrapper::METHOD_DELETE, $url);
    }

    private function getSignatureAndVersionHeaders()
    {
        $signature = $this->signatureService->getSignatureForApi();
        return array(
            static::HEADER_SIGNATURE . ': ' . $signature->getHash(),
            static::HEADER_SIGNATURE_DATE . ': ' . $signature->getDate(),
            static::HEADER_PLATFORM . ': ' . 'php_' . TheClient::VERSION
        );
    }
}
