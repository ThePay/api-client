<?php


namespace ThePay\ApiClient\Model;

class SignedData
{
    /** @var string */
    private $data;

    /** @var string */
    private $signature;

    /**
     * SignedData constructor.
     *
     * @param string $data
     * @param string $signature
     */
    public function __construct($data, $signature)
    {
        $this->data = $data;
        $this->signature = $signature;
    }

    /**
     * @return string
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return string
     */
    public function getSignature()
    {
        return $this->signature;
    }
}
