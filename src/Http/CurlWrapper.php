<?php

namespace ThePay\ApiClient\Http;

use ThePay\ApiClient\TheClient;

/**
 * @deprecated will be replaced by https://www.php-fig.org/psr/psr-18/ interface
 */
class CurlWrapper
{
    /**
     * @deprecated will be private
     */
    public const METHOD_GET = 'GET';
    /**
     * @deprecated will be private
     */
    public const METHOD_POST = 'POST';
    /**
     * @deprecated will be private
     */
    public const METHOD_DELETE = 'DELETE';
    /**
     * @deprecated will be private
     */
    public const METHOD_PUT = 'PUT';
    /**
     * @deprecated will be removed
     */
    public const HEADER_HOST = 'Host';

    /** @var \CurlHandle */
    private $curl;

    /**
     * @var array<string>
     */
    private $defaultHeaders;

    /**
     * @deprecated will be replaced by https://www.php-fig.org/psr/psr-18/ interface
     *
     * @param \CurlHandle $curl
     * @param array<string> $defaultHeaders
     */
    public function __construct($curl, array $defaultHeaders = [])
    {
        $this->curl = $curl;
        $this->defaultHeaders = $defaultHeaders;
        $this->setDefaultOptions();
    }

    /**
     * @deprecated will be replaced by https://www.php-fig.org/psr/psr-18/ interface
     *
     * @param string $method
     * @param string $url
     * @param string $data
     * @param array<string> $headers
     * @return HttpResponse
     */
    public function request($method, $url, $data = '', array $headers = [])
    {
        switch ($method) {
            case self::METHOD_GET:
                curl_setopt($this->curl, CURLOPT_HTTPGET, true);
                break;
            case self::METHOD_POST:
                curl_setopt_array($this->curl, [
                    CURLOPT_POST => true,
                    CURLOPT_POSTFIELDS => $data,
                    CURLOPT_RETURNTRANSFER => true,
                ]);
                break;
            case self::METHOD_PUT:
                curl_setopt_array($this->curl, [
                    CURLOPT_CUSTOMREQUEST => 'PUT',
                    CURLOPT_POSTFIELDS => $data,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLINFO_HEADER_OUT => true,
                ]);
                break;
            default:
                curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, $method);
                break;
        }

        $headers = array_merge($this->defaultHeaders, $headers);

        if (array_key_exists('HTTP_HOST', $_SERVER)) {
            // todo : http://redmine.havelholding.cz/issues/2258
            //array_push($headers, static::HEADER_HOST . ': ' . $_SERVER['HTTP_HOST']);
        }

        curl_setopt($this->curl, CURLOPT_HTTPHEADER, $headers);

        curl_setopt_array($this->curl, [
            CURLOPT_URL => $url,
        ]);

        return $this->resolveResponse(curl_exec($this->curl), curl_error($this->curl));
    }

    /**
     * @return void
     */
    private function setDefaultOptions()
    {
        curl_setopt_array($this->curl, [
            CURLOPT_HEADER => true,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_USERAGENT => $this->getUserAgent(),
            CURLOPT_ENCODING => 'gzip, deflate, br',
            CURLINFO_HEADER_OUT => true,
        ]);
    }

    /**
     * @param string|bool $response
     * @param string $error
     * @return HttpResponse
     */
    private function resolveResponse($response, $error)
    {
        if ($response === false) {
            curl_close($this->curl);
            return new HttpResponse(null, null, $error, null, null);
        }

        $responseCode = curl_getinfo($this->curl, CURLINFO_HTTP_CODE);
        $headerSize = curl_getinfo($this->curl, CURLINFO_HEADER_SIZE);
        $header = substr($response, 0, $headerSize);
        $headers = [];
        $matches = [];
        $body = substr($response, $headerSize);
        $responseCodeMessage = '';

        if (preg_match("/^.+ \d+ (?P<codeMessage>[^\r\n]*)/m", $header, $matches)) {
            $responseCodeMessage = $matches['codeMessage'];
        }
        preg_match_all('/^(.+?\:)(?:\s)(.+)/m', $header, $matches);
        for ($i = 0; $i < count($matches[0]); $i++) {
            $headers[$matches[1][$i]] = $matches[2][$i];
        }
        curl_close($this->curl);

        return new HttpResponse($response, $responseCode, $responseCodeMessage, $headers, $body);
    }

    /**
     * @return string
     */
    private function getUserAgent()
    {
        return 'ThePay Client/' . TheClient::VERSION . ' (PHP version ' . phpversion() . ')';
    }
}
