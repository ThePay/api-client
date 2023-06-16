<?php

namespace ThePay\ApiClient\Model;

use DateTime;

class PaginatedCollectionParams implements SignableRequest
{
    /** @var int */
    private $limit;

    /** @var int */
    private $page;

    /**
     * @var SignableRequest
     */
    private $baseParams;

    /**
     * @param int $page
     * @param int $limit
     */
    public function __construct(SignableRequest $baseParams, $page = 1, $limit = 25)
    {
        $this->baseParams = $baseParams;
        $this->page = $page;
        $this->limit = $limit;
    }

    /**
     * @return int
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @param int $limit
     * @return void
     */
    public function setLimit($limit)
    {
        $this->limit = (int) $limit;
    }

    /**
     * @return int
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @param int $page
     * @return void
     */
    public function setPage($page)
    {
        $this->page = (int) $page;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray()
    {
        // we convert all dates to string
        $baseParams = array_map(function ($param) {
            if ($param instanceof DateTime) {
                return $param->format(DATE_ATOM);
            }
            return $param;
        }, $this->baseParams->toArray());

        return array_merge([
            'limit' => $this->limit,
            'page' => $this->page,
        ], $baseParams);
    }
}
