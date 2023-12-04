<?php

namespace ThePay\ApiClient\Model;

final class AccountBalance
{
    /** @var non-empty-string */
    private $iban;
    /** @var non-empty-string */
    private $accountName;
    /** @var array<non-empty-string, numeric-string> */
    private $balances;

    /**
     * @param non-empty-string $iban
     * @param non-empty-string $accountName
     * @param array<non-empty-string, numeric-string> $balances key is ISO 4217 currency code, value is balance in smallest unit of currency
     */
    public function __construct(
        $iban,
        $accountName,
        array $balances
    ) {
        $this->iban = $iban;
        $this->accountName = $accountName;
        $this->balances = $balances;
    }

    /**
     * @return non-empty-string
     */
    public function getIban()
    {
        return $this->iban;
    }

    /**
     * @return non-empty-string
     */
    public function getAccountName()
    {
        return $this->accountName;
    }

    /**
     * @return array<non-empty-string, numeric-string>
     */
    public function getBalances()
    {
        return $this->balances;
    }
}
