<?php

namespace ThePay\ApiClient\Model;

use ThePay\ApiClient\ValueObject\Url;

interface IPaymentMethod
{
    /**
     * @return string
     */
    public function getCode();

    /**
     * @return string
     */
    public function getTitle();

    /**
     * @return Url
     */
    public function getImageUrl();

    /**
     * @return array<string>
     */
    public function getTags();

    /**
     * @return array<string, mixed>
     */
    public function toArray();
}
