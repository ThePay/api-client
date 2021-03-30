<?php

namespace ThePay\ApiClient\Service;

use ThePay\ApiClient\Exception\ApiException;
use ThePay\ApiClient\Filter\PaymentsFilter;
use ThePay\ApiClient\Http\HttpServiceInterface;
use ThePay\ApiClient\Model\Collection\PaymentCollection;
use ThePay\ApiClient\Model\Collection\PaymentMethodCollection;
use ThePay\ApiClient\Model\CreatePaymentParams;
use ThePay\ApiClient\Model\CreatePaymentResponse;
use ThePay\ApiClient\Model\CreateRecurringPaymentParams;
use ThePay\ApiClient\Model\Payment;
use ThePay\ApiClient\Model\PaymentRefundInfo;
use ThePay\ApiClient\Model\RealizePreauthorizedPaymentParams;
use ThePay\ApiClient\Model\RealizeRecurringPaymentResponse;
use ThePay\ApiClient\TheConfig;
use ThePay\ApiClient\ValueObject\Amount;
use ThePay\ApiClient\ValueObject\Identifier;
use ThePay\ApiClient\ValueObject\LanguageCode;
use ThePay\ApiClient\ValueObject\PaymentMethodCode;
use ThePay\ApiClient\ValueObject\StringValue;

interface ApiServiceInterface
{
    public function __construct(TheConfig $config, HttpServiceInterface $httpService);

    /**
     * Fetch all active payment methods.
     *
     * @param LanguageCode|null $languageCode language for payment method titles, null value language from TheConfig used
     * @return PaymentMethodCollection
     * @throws ApiException
     */
    public function getActivePaymentMethods(LanguageCode $languageCode = null);

    /**
     * @param Identifier $paymentUid
     * @return Payment
     * @throws ApiException
     */
    public function getPayment(Identifier $paymentUid);

    /**
     * @param Identifier $paymentUid
     * @return Payment
     * @throws ApiException
     */
    public function invalidatePayment(Identifier $paymentUid);

    /**
     * @param PaymentsFilter $filter
     * @param int $page
     * @param null|int $limit
     * @return PaymentCollection
     * @throws ApiException
     */
    public function getPayments(PaymentsFilter $filter, $page = 1, $limit = null);

    /**
     * @param CreatePaymentParams $createPaymentParams
     * @return CreatePaymentResponse
     * @throws ApiException
     */
    public function createPayment(CreatePaymentParams $createPaymentParams);

    /**
     * @param RealizePreauthorizedPaymentParams $params
     * @return bool
     * @throws ApiException
     */
    public function realizePreauthorizedPayment(RealizePreauthorizedPaymentParams $params);

    /**
     * @param Identifier $uid
     * @return bool
     * @throws ApiException
     */
    public function cancelPreauthorizedPayment(Identifier $uid);

    /**
     * @return bool
     * @throws ApiException
     */
    public function changePaymentMethod(Identifier $uid, PaymentMethodCode $paymentMethodCode);

    /**
     * @param CreateRecurringPaymentParams $params
     *
     * @return RealizeRecurringPaymentResponse
     * @throws ApiException
     */
    public function realizeRecurringPayment(CreateRecurringPaymentParams $params);

    /**
     * Returns information about payment refund.
     *
     * @return PaymentRefundInfo
     */
    public function getPaymentRefund(Identifier $uid);

    /**
     * Will create request for automatic refund of payment.
     *
     * @param Amount $amount amount which should be refunded in cents (currency used for refunding is same as payment currency)
     * @return void
     */
    public function createPaymentRefund(Identifier $uid, Amount $amount, StringValue $reason);
}
