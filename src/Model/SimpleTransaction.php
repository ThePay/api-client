<?php

namespace ThePay\ApiClient\Model;

class SimpleTransaction
{
    /** @var int */
    private $transactionId;
    /** @var int */
    private $amount;
    /** @var string */
    private $currencyCode;
    /** @var string */
    private $transactionType;
    /** @var string */
    private $note;
    /** @var BankAccount  */
    private $offsetAccount;
    /** @var PaymentIdentificator */
    private $paymentIdentificator;
    /** @var \DateTime */
    private $realizedAt;
    /** @var string */
    private $vs;
    /** @var string */
    private $ss;
    /** @var string */
    private $ks;

    /**
     * SimpleTransaction constructor.
     *
     * @param array<string, mixed> $data
     * @throws \Exception
     */
    public function __construct(array $data)
    {
        $this->transactionId = $data['transaction_id'];
        $this->amount = $data['amount'];
        $this->currencyCode = $data['currency_code'];
        $this->transactionType = $data['transaction_type'];
        $this->note = $data['note'];
        $this->offsetAccount = empty($data['offset_account']) ? null : new BankAccount($data['offset_account']);
        $this->paymentIdentificator = empty($data['payment_identificator']) ? null : new PaymentIdentificator($data['payment_identificator']);
        $this->realizedAt = new \DateTime($data['realized_at']);
        $this->vs = $data['vs'];
        $this->ss = $data['ss'];
        $this->ks = $data['ks'];
    }

    /**
     * @return int
     */
    public function getTransactionId()
    {
        return $this->transactionId;
    }

    /**
     * @return int
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @return string
     */
    public function getCurrencyCode()
    {
        return $this->currencyCode;
    }

    /**
     * @return string
     */
    public function getTransactionType()
    {
        return $this->transactionType;
    }

    /**
     * @return string
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * @return BankAccount
     */
    public function getOffsetAccount()
    {
        return $this->offsetAccount;
    }

    /**
     * @return PaymentIdentificator
     */
    public function getPaymentIdentificator()
    {
        return $this->paymentIdentificator;
    }

    /**
     * @return \DateTime
     */
    public function getRealizedAt()
    {
        return $this->realizedAt;
    }

    /**
     * @return string
     */
    public function getVs()
    {
        return $this->vs;
    }

    /**
     * @return string
     */
    public function getSs()
    {
        return $this->ss;
    }

    /**
     * @return string
     */
    public function getKs()
    {
        return $this->ks;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray()
    {
        return array(
            'transactionId' => $this->transactionId,
            'amount' => $this->amount,
            'currencyCode' => $this->currencyCode,
            'transactionType' => $this->transactionType,
            'note' => $this->note,
            'offsetAccount' => $this->offsetAccount->toArray(),
            'paymentIdentificator' => $this->paymentIdentificator->toArray(),
            'realizedAt' => $this->realizedAt,
            'vs' => $this->vs,
            'ss' => $this->ss,
            'ks' => $this->ks,
        );
    }
}
