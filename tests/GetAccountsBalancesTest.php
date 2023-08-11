<?php

namespace ThePay\ApiClient\Tests;

use ThePay\ApiClient\Http\HttpResponse;
use ThePay\ApiClient\Http\HttpServiceInterface;
use ThePay\ApiClient\Model\AccountBalance;
use ThePay\ApiClient\TheClient;

final class GetAccountsBalancesTest extends BaseTestCase
{
    /**
     * @return void
     */
    public function test()
    {
        /** @var HttpServiceInterface $httpService */
        $httpService = \Mockery::mock('ThePay\ApiClient\Http\HttpServiceInterface');
        call_user_func(array($httpService, 'shouldReceive'), 'get')->once()
            ->with($this->config->getApiUrl() . 'balances?account_iban=TP7811112150822790787055&project_id=1&balance_at=2023-03-14T15%3A08%3A44%2B00%3A00&merchant_id=' . self::MERCHANT_ID)
            ->andReturn(
                new HttpResponse(
                    null,
                    200,
                    '',
                    null,
                    '[
                        {
                            "iban": "TP7811112150822790787055",
                            "name": "Test",
                            "balance": {
                                "CZK": "45899",
                                "EUR": "500"
                            }
                        }
                    ]'
                )
            );

        $client = new TheClient($this->config, null, $httpService);

        $balances = $client->getAccountsBalances('TP7811112150822790787055', 1, new \DateTime('2023-03-14 15:08:44+00:00'));
        self::assertEquals(
            array(
                new AccountBalance(
                    'TP7811112150822790787055',
                    'Test',
                    array('CZK' => '45899', 'EUR' => '500')
                ),
            ),
            $balances
        );

        \Mockery::close();
    }
}
