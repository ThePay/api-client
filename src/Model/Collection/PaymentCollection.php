<?php

namespace ThePay\ApiClient\Model\Collection;

use ThePay\ApiClient\Model\SimplePayment;
use ThePay\ApiClient\Utils\Json;

/**
 * @method add(SimplePayment $item)
 * @method SimplePayment[] all()
 */
final class PaymentCollection extends Collection implements Paginated
{
    /** @var int */
    private $page;

    /** @var int */
    private $recordsPerPage;

    /** @var int */
    private $totalCount;

    public function __construct($json, $page, $recordsPerPage, $totalCount)
    {
        $data = is_array($json) ? $json : Json::decode($json, true);

        foreach ($data as $d) {
            $this->add(is_object($d) ? $d : new SimplePayment($d));
        }

        $this->page = $page;
        $this->totalCount = $totalCount;
        $this->recordsPerPage = $recordsPerPage;
    }

    /**
     * @return int
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @return int
     */
    public function getRecordsPerPage()
    {
        return $this->recordsPerPage;
    }

    /**
     * @return int
     */
    public function getTotalCount()
    {
        return $this->totalCount;
    }

    /**
     * @return bool
     */
    public function hasNextPage()
    {
        return $this->page * $this->recordsPerPage < $this->totalCount;
    }
}
