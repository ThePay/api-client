<?php

namespace ThePay\ApiClient\Model;

use ThePay\ApiClient\ValueObject\PhoneNumber;
use ThePay\ApiClient\ValueObject\StringValue;

final class CreatePaymentCustomer
{
    /** @var StringValue|null */
    private $name;
    /** @var StringValue|null */
    private $surname;
    /** @var StringValue|null */
    private $email;
    /** @var PhoneNumber|null */
    private $phone;
    /** @var Address|null */
    private $billingAddress;
    /** @var Address|null */
    private $shippingAddress;

    /**
     * @param string|null $name
     * @param string|null $surname
     * @param string|null $email
     * @param string|null $phone - customer phone in international format max 15 numeric chars https://en.wikipedia.org/wiki/MSISDN
     */
    public function __construct($name, $surname, $email, $phone, Address $billingAddress = null, Address $shippingAddress = null)
    {
        $this->name = $name === null ? null : new StringValue($name);
        $this->surname = $surname === null ? null : new StringValue($surname);
        $this->email = $email === null ? null : new StringValue($email);
        $this->phone = $phone === null ? null : new PhoneNumber($phone);
        $this->billingAddress = $billingAddress;
        $this->shippingAddress = $shippingAddress;
    }

    /**
     * @return string|null
     */
    public function getName()
    {
        return $this->name === null ? null : $this->name->getValue();
    }

    /**
     * @return string|null
     */
    public function getSurname()
    {
        return $this->surname === null ? null : $this->surname->getValue();
    }

    /**
     * @return string|null
     */
    public function getEmail()
    {
        return $this->email === null ? null : $this->email->getValue();
    }

    /**
     * @return string|null
     */
    public function getPhone()
    {
        return $this->phone === null ? null : $this->phone->getValue();
    }

    /**
     * @return Address|null
     */
    public function getBillingAddress()
    {
        return $this->billingAddress;
    }

    /**
     * @return Address|null
     */
    public function getShippingAddress()
    {
        return $this->shippingAddress;
    }
}
