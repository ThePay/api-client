<?php

declare(strict_types=1);

namespace ThePay\ApiClient\Tests;

use ThePay\ApiClient\Model\AccountBalance;

final class GetAccountsBalancesTest extends BaseTestCase
{
    public function test(): void
    {
        $client = $this->getMockClient();

        $balances = $client->getAccountsBalances('TP7811112150822790787055', 1, new \DateTime('2023-03-14 15:08:44+00:00'));
        self::assertEquals(
            [
                new AccountBalance(
                    'TP7811112150822790787055',
                    'Account #1',
                    ['CZK' => '1256', 'EUR' => '231', 'USD' => '0']
                ),
            ],
            $balances
        );
    }
}
