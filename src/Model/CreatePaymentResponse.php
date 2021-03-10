<?php

namespace ThePay\ApiClient\Model;

use ThePay\ApiClient\Utils\Json;

class CreatePaymentResponse
{
    /** @var string */
    private $payUrl;

    /** @var string */
    private $detailUrl;

    /** @var bool */
    private $wasCreated;

    /**
     * CreatePaymentResponse constructor.
     *
     * @param string|array $values
     * @param bool $wasCreated
     */
    public function __construct($values, $wasCreated = true)
    {
        $data = is_array($values) ? $values : Json::decode($values, true);

        $this->payUrl = $data['pay_url'];
        $this->detailUrl = $data['detail_url'];
        $this->wasCreated = $wasCreated;
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

    /**
     * If false is returned then the payment with given UID has already existed prior to the API call.
     * If so then the payment was not updated and original payment urls are available in the response.
     *
     * @return bool
     */
    public function wasCreated()
    {
        return $this->wasCreated;
    }
}
