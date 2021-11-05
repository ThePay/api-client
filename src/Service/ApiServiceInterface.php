<?php

namespace ThePay\ApiClient\Service;

use ThePay\ApiClient\Exception\ApiException;
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
use ThePay\ApiClient\Model\PaymentRefundInfo;
use ThePay\ApiClient\Model\Project;
use ThePay\ApiClient\Model\RealizeIrregularSubscriptionPaymentParams;
use ThePay\ApiClient\Model\RealizePaymentBySavedAuthorizationParams;
use ThePay\ApiClient\Model\RealizePreauthorizedPaymentParams;
use ThePay\ApiClient\Model\RealizeRegularSubscriptionPaymentParams;
use ThePay\ApiClient\Model\RealizeUsageBasedSubscriptionPaymentParams;
use ThePay\ApiClient\Model\SimplePayment;
use ThePay\ApiClient\Model\SimpleTransaction;
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
     * Fetch all projects for merchant set in TheConfig
     *
     * @return Project[]
     */
    public function getProjects();

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
     * @return void
     * @throws ApiException
     */
    public function invalidatePayment(Identifier $paymentUid);

    /**
     * @param Identifier $parentPaymentUid UID of payment which initialized this subscription.
     * @param RealizeRegularSubscriptionPaymentParams $params
     * @return ApiResponse
     * @throws ApiException
     */
    public function realizeRegularSubscriptionPayment(Identifier $parentPaymentUid, RealizeRegularSubscriptionPaymentParams $params);

    /**
     * @param Identifier $parentPaymentUid UID of payment which initialized this subscription.
     * @param RealizeIrregularSubscriptionPaymentParams $params
     * @return ApiResponse
     * @throws ApiException
     */
    public function realizeIrregularSubscriptionPayment(Identifier $parentPaymentUid, RealizeIrregularSubscriptionPaymentParams $params);

    /**
     * @param Identifier $parentPaymentUid UID of payment which initialized this subscription.
     * @param RealizeUsageBasedSubscriptionPaymentParams $params
     * @return ApiResponse
     * @throws ApiException
     */
    public function realizeUsageBasedSubscriptionPayment(Identifier $parentPaymentUid, RealizeUsageBasedSubscriptionPaymentParams $params);

    /**
     * @param Identifier $parentPaymentUid UID of first payment created with save_authorization=true.
     * @param RealizePaymentBySavedAuthorizationParams $params
     * @return ApiResponse
     * @throws ApiException
     */
    public function realizePaymentBySavedAuthorization(Identifier $parentPaymentUid, RealizePaymentBySavedAuthorizationParams $params);

    /**
     * @param PaymentsFilter $filter
     * @param int $page
     * @param null|int $limit
     * @return PaymentCollection<SimplePayment>
     * @throws ApiException
     */
    public function getPayments(PaymentsFilter $filter, $page = 1, $limit = null);

    /**
     * @param TransactionFilter $filter
     * @param int $page
     * @param null|int $limit
     * @return TransactionCollection<SimpleTransaction>
     */
    public function getAccountTransactionHistory(TransactionFilter $filter, $page = 1, $limit = null);

    /**
     * @param CreatePaymentParams $createPaymentParams
     * @return CreatePaymentResponse
     * @throws ApiException
     */
    public function createPayment(CreatePaymentParams $createPaymentParams, PaymentMethodCode $paymentMethod = null);

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
