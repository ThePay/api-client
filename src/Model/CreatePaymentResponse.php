<?php

namespace ThePay\ApiClient\Model;

use ThePay\ApiClient\Utils\Json;

class CreatePaymentResponse
{
    /** @var string */
    private $payUrl;

    /** @var string */
    private $detailUrl;

    /**
     * CreatePaymentResponse constructor.
     *
     * @param string|array $values
     */
    public function __construct($values)
    {
        $data = is_array($values) ? $values : Json::decode($values, true);

        $this->payUrl = $data['pay_url'];
        $this->detailUrl = $data['detail_url'];
    }

    /**
     * @return string
     */
    public function getPayUrl()
    {
        return $this->payUrl;
    }

    /**
     * @return string
     */
    public function getPaymentDetailUrl()
    {
        return $this->detailUrl;
    }
}
