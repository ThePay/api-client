<?php

namespace ThePay\ApiClient\Model;

use ThePay\ApiClient\ValueObject\CountryCode;
use ThePay\ApiClient\ValueObject\StringValue;

final class Address
{
    /** @var CountryCode */
    private $countryCode;
    /** @var StringValue */
    private $city;
    /** @var StringValue */
    private $zip;
    /** @var StringValue */
    private $street;

    /**
     * @param string $countryCode 2 letter UPPERCASE country code - ISO 3166-1 (alpha-2) format e.g. "GB"
     * @param string $city max 100 chars city name
     * @param string $zip max 20 chars zip of the city
     * @param string $street max 100 chars street name with house number
     */
    public function __construct($countryCode, $city, $zip, $street)
    {
        $this->countryCode = new CountryCode($countryCode);
        $this->city = new StringValue($city);
        $this->zip = new StringValue($zip);
        $this->street = new StringValue($street);
    }

    /**
     * @return string
     */
    public function getCountryCode()
    {
        return $this->countryCode->getValue();
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city->getValue();
    }

    /**
     * @return string
     */
    public function getZip()
    {
        return $this->zip->getValue();
    }

    /**
     * @return string
     */
    public function getStreet()
    {
        return $this->street->getValue();
    }
}
