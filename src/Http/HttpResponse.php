<?php

namespace ThePay\ApiClient\Http;

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
     * @var array|null
     */
    private $headers;

    /**
     * @var string|null
     */
    private $body;

    /**
     * HttpResponse constructor.
     * @param string|null $response
     * @param int|null $code
     * @param string $codeMessage
     * @param array|null $headers
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
     * @return string|null
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @param string|null $response
     * @return self
     */
    public function setResponse($response)
    {
        $this->response = $response;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getCodeMessage()
    {
        return $this->codeMessage;
    }

    /**
     * @param int|null $code
     * @return self
     */
    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return array|null
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @return string|null
     */
    public function getBody()
    {
        return $this->body;
    }
}
