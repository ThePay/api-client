<?php

namespace ThePay\ApiClient\Model;

use ThePay\ApiClient\ValueObject\StringValue;

final class CreatePaymentCustomer
{
    /** @var StringValue */
    private $name;
    /** @var StringValue */
    private $surname;
    /** @var StringValue */
    private $email;
    /** @var StringValue */
    private $phone;
    /** @var Address|null */
    private $billingAddress;

    /**
     * @param string $name
     * @param string $surname
     * @param string $email
     * @param string $phone - up to 15 digits, no other characters allowed
     */
    public function __construct($name, $surname, $email, $phone, Address $billingAddress = null)
    {
        $this->name = new StringValue($name);
        $this->surname = new StringValue($surname);
        $this->email = new StringValue($email);
        $this->phone = new StringValue($phone);
        $this->billingAddress = $billingAddress;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name->getValue();
    }

    /**
     * @return string
     */
    public function getSurname()
    {
        return $this->surname->getValue();
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email->getValue();
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone->getValue();
    }

    /**
     * @return Address|null
     */
    public function getBillingAddress()
    {
        return $this->billingAddress;
    }
}
