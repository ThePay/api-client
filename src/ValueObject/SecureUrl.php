<?php

namespace ThePay\ApiClient\ValueObject;

final class SecureUrl extends BaseValueObject
{
    /** @var string */
    private $url;

    /**
     * @param string $url
     */
    public function __construct($url)
    {
        if ( ! filter_var($url, FILTER_VALIDATE_URL)) {
            throw new \InvalidArgumentException('Url is in incorrect format');
        }
        if (strpos($url, 'https://') !== 0) {
            throw new \InvalidArgumentException('Url must start with "https://"');
        }

        $this->url = $url;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getValue();
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->url;
    }
}
