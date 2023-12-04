<?php

namespace ThePay\ApiClient\Http;

/**
 * @deprecated will be replaced by https://www.php-fig.org/psr/psr-7/ interface
 */
class HttpResponse
{
    /**
     * @var string|null
     */
    private $response;

    /**
     * @var int|null
     */
    private $code;

    /**
     * @var string
     */
    private $codeMessage;

    /**
     * @var array<string, string>|null
     */
    private $headers;

    /**
     * @var string|null
     */
    private $body;

    /**
     * HttpResponse constructor.
     *
     * @deprecated will be replaced by https://www.php-fig.org/psr/psr-7/ interface
     *
     * @param string|null $response
     * @param int|null $code
     * @param string $codeMessage
     * @param array<string, string>|null $headers
     * @param string|null $body
     */
    public function __construct($response = null, $code = null, $codeMessage = '', array $headers = null, $body = null)
    {
        $this->response = $response;
        $this->code = $code;
        $this->codeMessage = $codeMessage;
        $this->headers = $headers;
        $this->body = $body;
    }

    /**
     * @deprecated will be replaced by https://www.php-fig.org/psr/psr-7/ interface
     *
     * @return string|null
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @deprecated will be replaced by https://www.php-fig.org/psr/psr-7/ interface
     *
     * @param string|null $response
     * @return self
     */
    public function setResponse($response)
    {
        $this->response = $response;
        return $this;
    }

    /**
     * @deprecated will be replaced by https://www.php-fig.org/psr/psr-7/ interface
     *
     * @return int|null
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @deprecated will be replaced by https://www.php-fig.org/psr/psr-7/ interface
     *
     * @return string
     */
    public function getCodeMessage()
    {
        return $this->codeMessage;
    }

    /**
     * @deprecated will be replaced by https://www.php-fig.org/psr/psr-7/ interface
     *
     * @param int|null $code
     * @return self
     */
    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @deprecated will be replaced by https://www.php-fig.org/psr/psr-7/ interface
     *
     * @return array<string, string>|null
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @deprecated will be replaced by https://www.php-fig.org/psr/psr-7/ interface
     *
     * @param non-empty-string $key
     * @return string|null
     */
    public function getHeader($key)
    {
        foreach ((array) $this->headers as $headerKey => $header) {
            if (strtolower($headerKey) === strtolower($key)) {
                return $header;
            }
        }

        return null;
    }

    /**
     * @deprecated will be replaced by https://www.php-fig.org/psr/psr-7/ interface
     *
     * @return string|null
     */
    public function getBody()
    {
        return $this->body;
    }
}
