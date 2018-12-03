<?php


namespace ThePay\ApiClient\Model\Collection;

interface Paginated
{
    /** @return int */
    public function getPage();

    /** @return int */
    public function getRecordsPerPage();

    /** @return int */
    public function getTotalCount();

    /** @return bool */
    public function hasNextPage();
}
