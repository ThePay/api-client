<?php

namespace ThePay\ApiClient\Model;

interface SignableRequest
{
    /** @return array The associative array of all parameters */
    public function toArray();
}
