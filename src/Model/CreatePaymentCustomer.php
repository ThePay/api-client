<?php

namespace ThePay\ApiClient\Model;

use InvalidArgumentException;
use ThePay\ApiClient\ValueObject\PhoneNumber;
use ThePay\ApiClient\ValueObject\StringValue;

final class CreatePaymentCustomer
{
    private string $name;
    private string $surname;
    /** @var StringValue|null */
    private $email;
    /** @var PhoneNumber|null */
    private $phone;
    /** @var Address|null */
    private $billingAddress;
    /** @var Address|null */
    private $shippingAddress;

    /**
     * @note At least one of $email and $phone is required.
     *
     * @param string|null $email
     * @param string|null $phone - customer phone in international format max 15 numeric chars https://en.wikipedia.org/wiki/MSISDN
     */
    public function __construct(string $name, string $surname, $email, $phone, Address $billingAddress = null, Address $shippingAddress = null)
    {
        if ($email === null && $phone === null) {
            throw new InvalidArgumentException('At least one of $email and $phone is required.');
        }

        $this->name = $name;
        $this->surname = $surname;
        $this->email = $email === null ? null : new StringValue($email);
        $this->phone = $phone === null ? null : new PhoneNumber($phone);
        $this->billingAddress = $billingAddress;
        $this->shippingAddress = $shippingAddress;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSurname(): string
    {
        return $this->surname;
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
