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
     * @return void
     */
    public function testHasNextPage($page, $recordsPerPage, $totalCount, $expected)
    {
        $paginatedCollection = new Collection\PaymentCollection('[]', $page, $recordsPerPage, $totalCount);

        static::assertEquals($expected, $paginatedCollection->hasNextPage());
    }

    /**
     * @return array<array<mixed>>
     */
    public static function getHasNextPageData(): array
    {
        return [
            [1, 10, 100, true],
            [10, 10, 100, false],
        ];
    }
}
