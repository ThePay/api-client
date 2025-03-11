<?php

namespace ThePay\ApiClient\Model;

use InvalidArgumentException;
use ThePay\ApiClient\ValueObject\EmailAddress;
use ThePay\ApiClient\ValueObject\NonEmptyString;
use ThePay\ApiClient\ValueObject\PhoneNumber;

final class CreatePaymentCustomer
{
    private string $name;
    private string $surname;
    /** @var string|null */
    private $email;
    /** @var string|null */
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
    public function __construct(string $name, string $surname, $email, $phone, ?Address $billingAddress = null, ?Address $shippingAddress = null)
    {
        if ($email === null && $phone === null) {
            throw new InvalidArgumentException('At least one of $email and $phone is required.');
        }

        $this->name = (new NonEmptyString($name))->getValue();
        $this->surname = (new NonEmptyString($surname))->getValue();
        $this->email = $email === null ? null : (new EmailAddress($email))->getValue();
        $this->phone = $phone === null ? null : (new PhoneNumber($phone))->getValue();
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
        return $this->email;
    }

    /**
     * @return string|null
     */
    public function getPhone()
    {
        return $this->phone;
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
