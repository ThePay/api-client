<?php

namespace ThePay\ApiClient\Service;

use ThePay\ApiClient\Exception\ApiException;
use ThePay\ApiClient\Filter\PaymentsFilter;
use ThePay\ApiClient\Filter\TransactionFilter;
use ThePay\ApiClient\Model\ApiResponse;
use ThePay\ApiClient\Model\Collection\PaymentCollection;
use ThePay\ApiClient\Model\Collection\PaymentMethodCollection;
use ThePay\ApiClient\Model\Collection\TransactionCollection;
use ThePay\ApiClient\Model\CreatePaymentParams;
use ThePay\ApiClient\Model\CreatePaymentResponse;
use ThePay\ApiClient\Model\Payment;
use ThePay\ApiClient\Model\PaymentMethodWithPayUrl;
use ThePay\ApiClient\Model\PaymentRefundInfo;
use ThePay\ApiClient\Model\Project;
use ThePay\ApiClient\Model\RealizeIrregularSubscriptionPaymentParams;
use ThePay\ApiClient\Model\RealizePaymentBySavedAuthorizationParams;
use ThePay\ApiClient\Model\RealizePreauthorizedPaymentParams;
use ThePay\ApiClient\Model\RealizeRegularSubscriptionPaymentParams;
use ThePay\ApiClient\Model\RealizeUsageBasedSubscriptionPaymentParams;
use ThePay\ApiClient\ValueObject\Amount;
use ThePay\ApiClient\ValueObject\Identifier;
use ThePay\ApiClient\ValueObject\LanguageCode;

interface ApiServiceInterface
{
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
     * @param int<1, max> $page
     * @param int<1, 1000> $limit
     */
    public function getPayments(PaymentsFilter $filter, int $page = 1, int $limit = 25): PaymentCollection;

    /**
     * @param int<1, max> $page
     * @param int<1, 1000> $limit
     */
    public function getAccountTransactionHistory(TransactionFilter $filter, int $page = 1, int $limit = 100): TransactionCollection;

    /**
     * @param non-empty-string|null $methodCode
     *
     * @throws ApiException
     */
    public function createPayment(CreatePaymentParams $createPaymentParams, ?string $methodCode = null): CreatePaymentResponse;

    public function realizePreauthorizedPayment(RealizePreauthorizedPaymentParams $params): void;

    public function cancelPreauthorizedPayment(Identifier $uid): void;

    /**
     * @param non-empty-string $methodCode
     *
     * @throws ApiException
     */
    public function changePaymentMethod(Identifier $uid, string $methodCode): void;

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
     */
    public function createPaymentRefund(Identifier $uid, Amount $amount, string $reason): void;

    /**
     * Returns an array of available payment methods with pay URLs for certain payment.
     *
     * @return array<PaymentMethodWithPayUrl>
     */
    public function getPaymentUrlsForPayment(Identifier $uid, LanguageCode $languageCode = null);
}
