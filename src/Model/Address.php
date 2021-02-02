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
     * @param string $countryCode - 2 letter UPPERCASE country code
     * @param string $city
     * @param string $zip
     * @param string $street
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
