<?php

namespace ThePay\ApiClient\Model;

use ThePay\ApiClient\Utils\Json;

class BankAccount
{
    /** @var string */
    private $iban;

    /** @var string */
    private $ownerName;

    /**
     * @param string|array $values Json in string or associative array
     */
    public function __construct($values)
    {
        $data = is_array($values) ? $values : Json::decode($values, true);

        $this->iban = $data['iban'];
        $this->ownerName = $data['owner_name'];
    }

    /**
     * @return string
     */
    public function getIban()
    {
        return $this->iban;
    }

    /**
     * @param string $iban
     * @return self
     */
    public function setIban($iban)
    {
        $this->iban = $iban;
        return $this;
    }

    /**
     * @return string
     */
    public function getOwnerName()
    {
        return $this->ownerName;
    }

    /**
     * @param string $ownerName
     * @return self
     */
    public function setOwnerName($ownerName)
    {
        $this->ownerName = $ownerName;
        return $this;
    }

    /**
     * @return array The associative array of all parameters
     */
    public function toArray()
    {
        return array(
            'iban' => $this->iban,
            'ownerName' => $this->ownerName,
        );
    }
}
