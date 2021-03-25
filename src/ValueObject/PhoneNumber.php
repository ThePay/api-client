<?php

namespace ThePay\ApiClient\ValueObject;

use InvalidArgumentException;

final class PhoneNumber extends BaseValueObject
{
    /** @var string */
    private $phone;

    public function __construct($phone)
    {
        $phone = str_replace(array('+', ' '), '', $phone);

        if ( ! preg_match('@^\d{1,15}$@', $phone)) {
            throw new InvalidArgumentException('Phone number: "' . $phone . '" is not in correct MSISDN format.');
        }

        $this->phone = $phone;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->phone;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->phone;
    }
}
