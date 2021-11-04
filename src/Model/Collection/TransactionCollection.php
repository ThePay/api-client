<?php

namespace ThePay\ApiClient\Model\Collection;

use ThePay\ApiClient\Model\SimpleTransaction;

/**
 * @extends Collection<SimpleTransaction>
 */
final class TransactionCollection extends Collection implements Paginated
{
    /** @var int */
    private $page;

    /** @var int */
    private $recordsPerPage;

    /** @var int */
    private $totalCount;

    /**
     * TransactionCollection constructor.
     * @param array<array<string, mixed>> $data
     * @param int $page
     * @param int $recordsPerPage
     * @param int $totalCount
     * @throws \Exception
     */
    public function __construct(array $data, $page, $recordsPerPage, $totalCount)
    {
        foreach ($data as $d) {
            $this->add(new SimpleTransaction($d));
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
