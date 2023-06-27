<?php

namespace ThePay\ApiClient\Model;

use ThePay\ApiClient\Utils\Json;
use ThePay\ApiClient\ValueObject\Url;

class PaymentMethodWithPayUrl implements IPaymentMethod
{
    /** @var string */
    private $code;

    /** @var string */
    private $title;

    /** @var array<string> */
    private $tags;

    /** @var Url|null */
    private $imageUrl;

    /** @var Url */
    private $payUrl;

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
        $this->payUrl = new Url($data['url']);
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

    /** @return Url */
    public function getPayUrl()
    {
        return $this->payUrl;
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
            'imageUrl' => (string) $this->imageUrl,
            'payUrl' => (string) $this->payUrl,
        ];
    }
}
