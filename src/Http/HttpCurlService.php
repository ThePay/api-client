<?php

namespace ThePay\ApiClient\Http;

use ThePay\ApiClient\Service\SignatureService;
use ThePay\ApiClient\TheClient;

/**
 * Class HttpCurlService is simple curl wrapper
 *
 * @deprecated will be replaced by https://www.php-fig.org/psr/psr-18/ interface
 */
class HttpCurlService implements HttpServiceInterface
{
    /**
     * @deprecated will be private
     */
    public const HEADER_SIGNATURE = 'Signature';
    /**
     * @deprecated will be private
     */
    public const HEADER_SIGNATURE_DATE = 'SignatureDate';
    /**
     * @deprecated will be private
     */
    public const HEADER_PLATFORM = 'Platform';

    /** @var SignatureService */
    private $signatureService;

    /** @var CurlWrapperFactory */
    private $curlWrapperFactory;

    /**
     * HttpCurlService constructor.
     *
     * @deprecated will be replaced by https://www.php-fig.org/psr/psr-18/ interface
     *
     * @param SignatureService $signatureService
     */
    public function __construct(SignatureService $signatureService)
    {
        $this->signatureService = $signatureService;
        $this->curlWrapperFactory = new CurlWrapperFactory();
    }

    /**
     * @deprecated will be replaced by https://www.php-fig.org/psr/psr-18/ interface
     *
     * @param string $url
     * @return HttpResponse
     */
    public function get($url)
    {
        $curl = $this->curlWrapperFactory->create($this->getSignatureAndVersionHeaders());
        return $curl->request(CurlWrapper::METHOD_GET, $url);
    }

    /**
     * @deprecated will be replaced by https://www.php-fig.org/psr/psr-18/ interface
     *
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
     * @deprecated will be replaced by https://www.php-fig.org/psr/psr-18/ interface
     *
     * @param string $url
     * @param string $data put request body content
     * @return HttpResponse
     */
    public function put($url, $data = '')
    {
        $curl = $this->curlWrapperFactory->create($this->getSignatureAndVersionHeaders());
        return $curl->request(CurlWrapper::METHOD_PUT, $url, $data);
    }

    /**
     * @deprecated will be replaced by https://www.php-fig.org/psr/psr-18/ interface
     *
     * @param string $url
     * @return HttpResponse
     */
    public function delete($url)
    {
        $curl = $this->curlWrapperFactory->create($this->getSignatureAndVersionHeaders());
        return $curl->request(CurlWrapper::METHOD_DELETE, $url);
    }

    /**
     * @return array<string>
     */
    private function getSignatureAndVersionHeaders()
    {
        $signature = $this->signatureService->getSignatureForApi();
        return [
            static::HEADER_SIGNATURE . ': ' . $signature->getHash(),
            static::HEADER_SIGNATURE_DATE . ': ' . $signature->getDate(),
            static::HEADER_PLATFORM . ': ' . 'php_' . TheClient::VERSION,
        ];
    }
}
