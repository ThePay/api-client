<?php

namespace ThePay\ApiClient\Tests\Mocks\Service;

use ThePay\ApiClient\Filter\PaymentsFilter;
use ThePay\ApiClient\Filter\TransactionFilter;
use ThePay\ApiClient\Http\HttpServiceInterface;
use ThePay\ApiClient\Model\ApiResponse;
use ThePay\ApiClient\Model\Collection\PaymentCollection;
use ThePay\ApiClient\Model\Collection\PaymentMethodCollection;
use ThePay\ApiClient\Model\Collection\TransactionCollection;
use ThePay\ApiClient\Model\CreatePaymentParams;
use ThePay\ApiClient\Model\CreatePaymentResponse;
use ThePay\ApiClient\Model\Payment;
use ThePay\ApiClient\Model\PaymentMethodWithPayUrl;
use ThePay\ApiClient\Model\PaymentRefund;
use ThePay\ApiClient\Model\PaymentRefundInfo;
use ThePay\ApiClient\Model\Project;
use ThePay\ApiClient\Model\RealizeIrregularSubscriptionPaymentParams;
use ThePay\ApiClient\Model\RealizePaymentBySavedAuthorizationParams;
use ThePay\ApiClient\Model\RealizePreauthorizedPaymentParams;
use ThePay\ApiClient\Model\RealizeRegularSubscriptionPaymentParams;
use ThePay\ApiClient\Model\RealizeUsageBasedSubscriptionPaymentParams;
use ThePay\ApiClient\Service\ApiServiceInterface;
use ThePay\ApiClient\TheConfig;
use ThePay\ApiClient\ValueObject\Amount;
use ThePay\ApiClient\ValueObject\Identifier;
use ThePay\ApiClient\ValueObject\LanguageCode;
use ThePay\ApiClient\ValueObject\PaymentMethodCode;
use ThePay\ApiClient\ValueObject\PaymentRefundState;
use ThePay\ApiClient\ValueObject\StringValue;

class ApiMockService implements ApiServiceInterface
{
    /** @phpstan-ignore-next-line -- never used (never mind) */
    public function __construct(TheConfig $config, HttpServiceInterface $httpService)
    {
    }

    /**
     * @return Project[]
     */
    public function getProjects()
    {
        return array(new Project(1, 'https://somedomain.cz', 'TP5911113021710162319866'));
    }

    /**
     * Fetch all active payment methods.
     *
     * @return PaymentMethodCollection
     */
    public function getActivePaymentMethods(LanguageCode $languageCode = null)
    {
        return new PaymentMethodCollection(
            array(
                0 =>
                    array(
                        'code' => 'test_online',
                        'title' => 'shared::payment_methods.test_online',
                        'tags' =>
                            array(
                                0 => 'access_account_owner',
                                1 => 'online',
                                2 => 'returnable',
                            ),
                        'available_currencies' =>
                            array(
                                0 =>
                                    array(
                                        'code' => 'CZK',
                                        'numeric_code' => '203',
                                    ),
                            ),
                        'image' =>
                            array(
                                'src' => 'http://localhost:8000/img/payment_methods/test_online.png',
                            ),
                    ),
                1 =>
                    array(
                        'code' => 'test_offline',
                        'title' => 'shared::payment_methods.test_offline',
                        'tags' =>
                            array(
                                0 => 'access_account_owner',
                                1 => 'returnable',
                            ),
                        'available_currencies' =>
                            array(
                                0 =>
                                    array(
                                        'code' => 'CZK',
                                        'numeric_code' => '203',
                                    ),
                            ),
                        'image' =>
                            array(
                                'src' => 'http://localhost:8000/img/payment_methods/test_offline.png',
                            ),
                    ),
                2 =>
                    array(
                        'code' => 'card',
                        'title' => 'Platba kartou',
                        'tags' =>
                            array(
                                0 => 'card',
                                1 => 'online',
                                2 => 'pre_authorization',
                                3 => 'recurring_payments',
                                4 => 'returnable',
                            ),
                        'available_currencies' =>
                            array(
                                0 =>
                                    array(
                                        'code' => 'CZK',
                                        'numeric_code' => '203',
                                    ),
                                1 =>
                                    array(
                                        'code' => 'GBP',
                                        'numeric_code' => '826',
                                    ),
                                2 =>
                                    array(
                                        'code' => 'USD',
                                        'numeric_code' => '840',
                                    ),
                                3 =>
                                    array(
                                        'code' => 'EUR',
                                        'numeric_code' => '978',
                                    ),
                            ),
                        'image' =>
                            array(
                                'src' => 'http://localhost:8000/img/payment_methods/card.png',
                            ),
                    ),
                3 =>
                    array(
                        'code' => 'super_cash',
                        'title' => 'SuperCASH',
                        'tags' =>
                            array(
                                0 => 'alternative_method',
                            ),
                        'available_currencies' =>
                            array(
                                0 =>
                                    array(
                                        'code' => 'CZK',
                                        'numeric_code' => '203',
                                    ),
                            ),
                        'image' =>
                            array(
                                'src' => 'http://localhost:8000/img/payment_methods/super_cash.png',
                            ),
                    ),
                4 =>
                    array(
                        'code' => 'platba_24',
                        'title' => 'shared::payment_methods.platba_24',
                        'tags' =>
                            array(
                                0 => 'access_account_owner',
                                1 => 'online',
                                2 => 'returnable',
                            ),
                        'available_currencies' =>
                            array(
                                0 =>
                                    array(
                                        'code' => 'CZK',
                                        'numeric_code' => '203',
                                    ),
                            ),
                        'image' =>
                            array(
                                'src' => 'http://localhost:8000/img/payment_methods/platba_24.png',
                            ),
                    ),
                5 =>
                    array(
                        'code' => 'bitcoin',
                        'title' => 'Platba Bitcoinem',
                        'tags' =>
                            array(
                                0 => 'alternative_method',
                                1 => 'online',
                                2 => 'returnable',
                            ),
                        'available_currencies' =>
                            array(
                                0 =>
                                    array(
                                        'code' => 'CZK',
                                        'numeric_code' => '203',
                                    ),
                            ),
                        'image' =>
                            array(
                                'src' => 'http://localhost:8000/img/payment_methods/bitcoin.png',
                            ),
                    ),
                6 =>
                    array(
                        'code' => 'csob',
                        'title' => 'ČSOB',
                        'tags' =>
                            array(
                                0 => 'access_account_owner',
                                1 => 'returnable',
                            ),
                        'available_currencies' =>
                            array(
                                0 =>
                                    array(
                                        'code' => 'CZK',
                                        'numeric_code' => '203',
                                    ),
                            ),
                        'image' =>
                            array(
                                'src' => 'http://localhost:8000/img/payment_methods/csob.png',
                            ),
                    ),
                7 =>
                    array(
                        'code' => 'equa_bank',
                        'title' => 'Equa Bank',
                        'tags' =>
                            array(
                                0 => 'access_account_owner',
                                1 => 'returnable',
                            ),
                        'available_currencies' =>
                            array(
                                0 =>
                                    array(
                                        'code' => 'CZK',
                                        'numeric_code' => '203',
                                    ),
                            ),
                        'image' =>
                            array(
                                'src' => 'http://localhost:8000/img/payment_methods/equa_bank.png',
                            ),
                    ),
                8 =>
                    array(
                        'code' => 'fio_banka',
                        'title' => 'Fio Banka',
                        'tags' =>
                            array(
                                0 => 'access_account_owner',
                                1 => 'online',
                                2 => 'returnable',
                            ),
                        'available_currencies' =>
                            array(
                                0 =>
                                    array(
                                        'code' => 'CZK',
                                        'numeric_code' => '203',
                                    ),
                            ),
                        'image' =>
                            array(
                                'src' => 'http://localhost:8000/img/payment_methods/fio_banka.png',
                            ),
                    ),
                9 =>
                    array(
                        'code' => 'mojeplatba',
                        'title' => 'MojePlatba',
                        'tags' =>
                            array(
                                0 => 'access_account_owner',
                                1 => 'online',
                                2 => 'returnable',
                            ),
                        'available_currencies' =>
                            array(
                                0 =>
                                    array(
                                        'code' => 'CZK',
                                        'numeric_code' => '203',
                                    ),
                            ),
                        'image' =>
                            array(
                                'src' => 'http://localhost:8000/img/payment_methods/mojeplatba.png',
                            ),
                    ),
                10 =>
                    array(
                        'code' => 'moneta',
                        'title' => 'Moneta',
                        'tags' =>
                            array(
                                0 => 'access_account_owner',
                                1 => 'returnable',
                            ),
                        'available_currencies' =>
                            array(
                                0 =>
                                    array(
                                        'code' => 'CZK',
                                        'numeric_code' => '203',
                                    ),
                            ),
                        'image' =>
                            array(
                                'src' => 'http://localhost:8000/img/payment_methods/moneta.png',
                            ),
                    ),
                11 =>
                    array(
                        'code' => 'mtransfer',
                        'title' => 'mTransfer',
                        'tags' =>
                            array(
                                0 => 'access_account_owner',
                                1 => 'online',
                                2 => 'returnable',
                            ),
                        'available_currencies' =>
                            array(
                                0 =>
                                    array(
                                        'code' => 'CZK',
                                        'numeric_code' => '203',
                                    ),
                            ),
                        'image' =>
                            array(
                                'src' => 'http://localhost:8000/img/payment_methods/mtransfer.png',
                            ),
                    ),
            )
        );
    }

    /**
     * Fetch all active payment methods.
     *
     * @return array<PaymentMethodWithPayUrl>
     */
    public function getPaymentUrlsForPayment(Identifier $uid, ?LanguageCode $languageCode = null)
    {
        return array(
            0 => new PaymentMethodWithPayUrl(
                array(
                    'code' => 'test_online',
                    'title' => 'shared::payment_methods.test_online',
                    'tags' =>
                        array(
                            0 => 'access_account_owner',
                            1 => 'online',
                            2 => 'returnable',
                        ),
                    'image' =>
                        array(
                            'src' => 'http://localhost:8000/img/payment_methods/test_online.png',
                        ),
                    'url' => 'http://localhost:8000/' . $uid->__toString() . '/update?payment_method_code=test_online',
                ),
            ),
            1 => new PaymentMethodWithPayUrl(
                array(
                    'code' => 'test_offline',
                    'title' => 'shared::payment_methods.test_offline',
                    'tags' =>
                        array(
                            0 => 'access_account_owner',
                            1 => 'returnable',
                        ),
                    'image' =>
                        array(
                            'src' => 'http://localhost:8000/img/payment_methods/test_offline.png',
                        ),
                    'url' => 'http://localhost:8000/' . $uid->__toString() . '/update?payment_method_code=test_offline',
                ),
            ),
            2 => new PaymentMethodWithPayUrl(
                array(
                    'code' => 'card',
                    'title' => 'Platba kartou',
                    'tags' =>
                        array(
                            0 => 'card',
                            1 => 'online',
                            2 => 'pre_authorization',
                            3 => 'recurring_payments',
                            4 => 'returnable',
                        ),
                    'image' =>
                        array(
                            'src' => 'http://localhost:8000/img/payment_methods/card.png',
                        ),
                    'url' => 'http://localhost:8000/' . $uid->__toString() . '/update?payment_method_code=card',
                ),
            ),
        );
    }

    /**
     * @param Identifier $paymentUid
     * @return Payment
     */
    public function getPayment(Identifier $paymentUid)
    {
        return new Payment(
            array(
                'uid' => 'efd7d8e6-2fa3-3c46-b475-51762331bf56',
                'project_id' => 1,
                'state' => 'paid',
                'currency' => 'CZK',
                'amount' => 876.54,
                'created_at' => '2019-01-01T12:00:00+00:00',
                'finished_at' => '2019-01-01T12:00:00+00:00',
                'valid_to' => '2019-01-01T12:00:00+00:00',
                'fee' => 12.1,
                'description' => 'Some sort of description',
                'order_id' => 'CZ12131415',
                'pay_url' => 'http://example.com',
                'detail_url' => 'http://example.com',
                'offset_account_determined_at' => null,
                'payment_method' => 'card',
                'offset_account_status' => 'loaded',
                'offset_account' =>
                    array(
                        'iban' => 'CZ65 0800 0000 1920 0014 5399',
                        'raw_account_number' => '1111/2010',
                        'owner_name' => 'The Master',
                    ),
                'customer' =>
                    array(
                        'account_iban' => 'CZ65 0800 0000 1920 0014 5399',
                        'name' => 'The Customer',
                        'ip' => '192.168.0.1',
                        'email' => '',
                    ),
                'events' =>
                    array(
                        array(
                            'occured_at' => '2021-05-03T10:23:27.000000Z',
                            'type' => 'payment_cancelled',
                            'data' => null,
                        ),
                    ),
            )
        );
    }

    public function getPayments(PaymentsFilter $filters, $page = 1, $limit = 25)
    {
        $paymentCollection = new PaymentCollection(
            array(
                array(
                    'uid' => 'efd7d8e6-2fa3-3c46-b475-51762331bf56',
                    'project_id' => 1,
                    'state' => 'paid',
                    'currency' => 'CZK',
                    'amount' => 876.54,
                    'created_at' => '2019-01-01T12:00:00+00:00',
                    'finished_at' => '2019-01-01T12:00:00+00:00',
                    'valid_to' => '2019-01-01T12:00:00+00:00',
                    'fee' => 12.1,
                    'description' => 'Some sort of description',
                    'pay_url' => 'http://example.com',
                    'detail_url' => 'http://example.com',
                    'order_id' => 'CZ12131415',
                    'payment_method' => 'card',
                    'offset_account_determined_at' => null,
                    'offset_account_status' => 'loaded',
                    'offset_account' =>
                        array(
                            'iban' => 'CZ65 0800 0000 1920 0014 5399',
                            'raw_account_number' => '1111/2010',
                            'owner_name' => 'The Master',
                        ),
                    'customer' =>
                        array(
                            'account_iban' => 'CZ65 0800 0000 1920 0014 5399',
                            'name' => 'The Customer',
                            'ip' => '192.168.0.1',
                            'email' => '',
                        ),
                    'events' =>
                        array(
                            array(
                                'occured_at' => '2021-05-03T10:23:27.000000Z',
                                'type' => 'payment_cancelled',
                                'data' => null,
                            ),
                        ),
                ),
            ),
            1,
            1,
            2
        );
        $paymentCollection->add($paymentCollection->offsetGet(0)); // Simulate 2 records
        return $paymentCollection;
    }

    public function createPayment(CreatePaymentParams $createPaymentParams, PaymentMethodCode $paymentMethod = null)
    {
        return new CreatePaymentResponse('{
          "pay_url": "https://gate.thepay.cz/",
          "detail_url": "https://gate.thepay.cz/"
        }');
    }

    public function realizePreauthorizedPayment(RealizePreauthorizedPaymentParams $params)
    {
        return true;
    }

    public function cancelPreauthorizedPayment(Identifier $uid)
    {
        return true;
    }

    public function changePaymentMethod(Identifier $uid, PaymentMethodCode $paymentMethodCode)
    {
        return true;
    }

    public function invalidatePayment(Identifier $paymentUid)
    {
    }

    public function realizeRegularSubscriptionPayment(Identifier $parentPaymentUid, RealizeRegularSubscriptionPaymentParams $params)
    {
        return new ApiResponse('{
          "state": "success",
          "message": "Ok"
        }', 200);
    }

    public function realizeIrregularSubscriptionPayment(Identifier $parentPaymentUid, RealizeIrregularSubscriptionPaymentParams $params)
    {
        return new ApiResponse('{
          "state": "success",
          "message": "Ok"
        }', 200);
    }

    public function realizeUsageBasedSubscriptionPayment(Identifier $parentPaymentUid, RealizeUsageBasedSubscriptionPaymentParams $params)
    {
        return new ApiResponse('{
          "state": "success",
          "message": "Ok"
        }', 200);
    }

    public function realizePaymentBySavedAuthorization(Identifier $parentPaymentUid, RealizePaymentBySavedAuthorizationParams $params)
    {
        return new ApiResponse('{
          "state": "success",
          "message": "Ok"
        }', 200);
    }

    /**
     * @return PaymentRefundInfo
     */
    public function getPaymentRefund(Identifier $uid)
    {
        return new PaymentRefundInfo(50000, 'CZK', array(new PaymentRefund(100000, 'Some reason', PaymentRefundState::RETURNED)));
    }

    public function createPaymentRefund(Identifier $uid, Amount $amount, StringValue $reason)
    {
    }

    /**
     * @param TransactionFilter $filter
     * @param int $page
     * @param null|int $limit
     * @return TransactionCollection
     */
    public function getAccountTransactionHistory(TransactionFilter $filter, $page = 1, $limit = null)
    {
        $transactionCollection = new TransactionCollection(
            array(
                array(
                    'transaction_id' => '35',
                    'amount' => 876.54,
                    'currency_code' => 'CZK',
                    'transaction_type' => 'payment',
                    'note' => 'Poznamka',
                    'realized_at' => '2021-05-01T12:00:00+00:00',
                    'vs' => '12365',
                    'ks' => '25',
                    'ss' => '3',
                    'offset_account' =>
                        array(
                            'iban' => 'CZ65 0800 0000 1920 0014 5399',
                            'owner_name' => 'The Master',
                        ),
                    'payment_identificator' =>
                        array(
                            'project_id' => '1',
                            'uid' => 'efd7d8e6-2fa3-3c46-b475-51762331bf56',
                        ),
                ),
            ),
            1,
            1,
            2
        );

        $transactionCollection->add($transactionCollection->offsetGet(0)); // Simulate 2 records
        return $transactionCollection;
    }
}
