<?php

namespace ThePay\ApiClient\Model;

class ApiSignature
{
    /** @var string */
    private $date;

    /** @var string */
    private $hash;

    /**
     * ApiSignature constructor.
     *
     * @param string $date date formatted in RFC7231
     * @param string $hash
     */
    public function __construct($date, $hash)
    {
        $this->date = (string) $date;
        $this->hash = (string) $hash;
    }

    /**
     * @return string
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @return string
     */
    public function getHash()
    {
        return $this->hash;
    }
}
