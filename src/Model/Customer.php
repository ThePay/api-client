<?php

namespace ThePay\ApiClient\Model;

use ThePay\ApiClient\Utils\Json;

class Customer
{
    /** @var string|null */
    private $accountIban;

    /** @var string|null */
    private $name;

    /** @var string|null */
    private $ip;

    /** @var string|null */
    private $email;

    /**
     * @param string|array<string, mixed> $values Json in string or associative array
     */
    public function __construct($values)
    {
        $data = is_array($values) ? $values : Json::decode($values, true);

        $this->accountIban = array_key_exists('account_iban', $data) ? $data['account_iban'] : null;
        $this->name = $data['name'];
        $this->ip = $data['ip'];
        $this->email = $data['email'];
    }

    /**
     * @return string|null
     */
    public function getAccountIban()
    {
        return $this->accountIban;
    }

    /**
     * @return string|null
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string|null
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * @return string|null
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray()
    {
        return [
            'accountIban' => $this->accountIban,
            'name' => $this->name,
            'ip' => $this->ip,
            'email' => $this->email,
        ];
    }
}
