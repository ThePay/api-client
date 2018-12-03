<?php

namespace ThePay\ApiClient\ValueObject;

use InvalidArgumentException;

final class Url extends BaseValueObject
{
    /** @var string */
    private $url;

    /**
     * @param string $url
     */
    public function __construct($url)
    {
        $url = trim($url);
        if ( ! filter_var($url, FILTER_VALIDATE_URL)) {
            throw new InvalidArgumentException('Url is in incorrect format');
        }
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->getValue();
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->url;
    }
}
