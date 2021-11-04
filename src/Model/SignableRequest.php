<?php

namespace ThePay\ApiClient\Model;

interface SignableRequest
{
    /**
     * @return array<string, mixed>
     */
    public function toArray();
}
