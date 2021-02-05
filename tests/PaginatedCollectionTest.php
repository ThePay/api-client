<?php

namespace ThePay\ApiClient\Tests;

use PHPUnit\Framework\TestCase;
use ThePay\ApiClient\Model\Collection;

class PaginatedCollectionTest extends TestCase
{
    /**
     * @dataProvider getHasNextPageData
     * @param int $page
     * @param int $recordsPerPage
     * @param int $totalCount
     * @param bool $expected
     */
    public function testHasNextPage($page, $recordsPerPage, $totalCount, $expected)
    {
        $paginatedCollection = new Collection\PaymentCollection('[]', $page, $recordsPerPage, $totalCount);

        static::assertEquals($expected, $paginatedCollection->hasNextPage());
    }

    /**
     * @return array[]
     */
    public function getHasNextPageData()
    {
        return array(
            array(1, 10, 100, true),
            array(10, 10, 100, false),
        );
    }
}
