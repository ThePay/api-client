<?php

namespace ThePay\ApiClient\Model;

use ThePay\ApiClient\Utils\Json;
use ThePay\ApiClient\ValueObject\Url;

class PaymentMethod implements IPaymentMethod
{
    /**
     * @deprecated use ThePay\ApiClient\ValueObject\PaymentMethodTag::CARD
     */
    public const TAG_CARD = 'card';
    /**
     * @deprecated use ThePay\ApiClient\ValueObject\PaymentMethodTag::ONLINE
     */
    public const TAG_ONLINE = 'online';
    /**
     * @deprecated use ThePay\ApiClient\ValueObject\PaymentMethodTag::PRE_AUTHORIZATION
     */
    public const TAG_PRE_AUTHORIZATION = 'pre_authorization';
    /**
     * @deprecated use ThePay\ApiClient\ValueObject\PaymentMethodTag::RETURNABLE
     */
    public const TAG_RETURNABLE = 'returnable';
    /**
     * @deprecated use ThePay\ApiClient\ValueObject\PaymentMethodTag::ACCESS_ACCOUNT_OWNER
     */
    public const TAG_ACCESS_ACOUNT_OWNER = 'access_account_owner';
    /**
     * @deprecated use ThePay\ApiClient\ValueObject\PaymentMethodTag::RECURRING_PAYMENTS
     */
    public const TAG_RECURRING_PAYMENTS = 'recurring_payments';
    /**
     * @deprecated use ThePay\ApiClient\ValueObject\PaymentMethodTag::ALTERNATIVE_METHOD
     */
    public const TAG_ALTERNATIVE_METHOD = 'alternative_method';

    /** @var string */
    private $code;

    /** @var string */
    private $title;

    /** @var array<string> */
    private $tags;

    /** @var string[] */
    private $availableCurrencies = [];

    /** @var Url|null */
    private $imageUrl;

    /**
     * @param string|array<string, mixed> $values Json in string or associative array
     */
    public function __construct($values)
    {
        $data = is_array($values) ? $values : Json::decode($values, true);

        $this->code = $data['code'];
        $this->title = $data['title'];
        $this->imageUrl = new Url($data['image']['src']);
        $this->tags = $data['tags'];
        foreach ($data['available_currencies'] as $currency) {
            $this->availableCurrencies[] = $currency['code'];
        }
    }

    /** @return string */
    public function getCode()
    {
        return $this->code;
    }

    /** @return string */
    public function getTitle()
    {
        return $this->title;
    }

    /** @return Url */
    public function getImageUrl()
    {
        return $this->imageUrl;
    }

    /**
     * @return array<string>
     */
    public function getAvailableCurrencies()
    {
        return $this->availableCurrencies;
    }

    /**
     * @return array<string>
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray()
    {
        return [
            'code' => $this->code,
            'title' => $this->title,
            'tags' => $this->tags,
            'availableCurrencies' => $this->availableCurrencies,
            'imageUrl' => (string) $this->imageUrl,
        ];
    }
}
