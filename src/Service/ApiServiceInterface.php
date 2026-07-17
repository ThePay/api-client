<?php

namespace ThePay\ApiClient\Service;

use Psr\Http\Message\StreamInterface;
use ThePay\ApiClient\Exception\ApiExceptionInterface;
use ThePay\ApiClient\Exception\NotFoundApiException;
use ThePay\ApiClient\Filter\PaymentsFilter;
use ThePay\ApiClient\Filter\TransactionFilter;
use ThePay\ApiClient\Model\AccountBalance;
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
use ThePay\ApiClient\Model\RealizePreauthorizedPaymentResult;
use ThePay\ApiClient\Model\RealizeRegularSubscriptionPaymentParams;
use ThePay\ApiClient\Model\RealizeUsageBasedSubscriptionPaymentParams;
use ThePay\ApiClient\Model\RecurringPaymentResult;
use ThePay\ApiClient\ValueObject\Amount;
use ThePay\ApiClient\ValueObject\GPCPaymentIdentifier;
use ThePay\ApiClient\ValueObject\Identifier;
use ThePay\ApiClient\ValueObject\LanguageCode;
use ThePay\ApiClient\ValueObject\StringValue;

interface ApiServiceInterface
{
    /**
     * Returns an array of project instances for your merchant account.
     *
     * @see https://docs.thepay.eu/#tag/Project-Info/paths/~1v1~1projects/get
     *
     * @return Project[]
     *
     * @throws ApiExceptionInterface
     */
    public function getProjects();

    /**
     * Returns a list of all available payment methods for your project.
     *
     * @see https://docs.thepay.eu/#tag/Project-Info/paths/~1v1~1projects~1%7Bproject_id%7D~1methods/get
     *
     * @param LanguageCode|null $languageCode language for payment method titles, null value language from TheConfig used
     *
     * @return PaymentMethodCollection
     *
     * @throws ApiExceptionInterface
     */
    public function getActivePaymentMethods(?LanguageCode $languageCode = null);

    /**
     * This resource represents one particular payment identified by its payment_uid.
     *
     * @see https://docs.thepay.eu/#tag/Payments/paths/~1v1~1projects~1%7Bproject_id%7D~1payments~1%7Bpayment_uid%7D/get
     *
     * @return Payment
     *
     * @throws NotFoundApiException|ApiExceptionInterface
     */
    public function getPayment(Identifier $paymentUid);

    /**
     * This endpoint will invalidate a payment if it is in `waiting_for_payment` state.
     *
     * @see https://docs.thepay.eu/#tag/General-Payment-Management/paths/~1v1~1projects~1%7Bproject_id%7D~1payments~1%7Bpayment_uid%7D~1invalidate/put
     *
     * @return void
     *
     * @throws NotFoundApiException|ApiExceptionInterface
     */
    public function invalidatePayment(Identifier $paymentUid);

    /**
     * This endpoint will realize new payment for the same subscription as its parent payment.
     *
     * @see https://docs.thepay.eu/#tag/Subscriptions/paths/~1v2~1projects~1%7Bproject_id%7D~1payments~1%7Bparent_payment_uid%7D~1subscription~1regular/post
     *
     * @param Identifier $parentPaymentUid UID of payment which initialized this subscription.
     *
     * @throws NotFoundApiException|ApiExceptionInterface
     */
    public function realizeRegularSubscriptionPayment(Identifier $parentPaymentUid, RealizeRegularSubscriptionPaymentParams $params): RecurringPaymentResult;

    /**
     * This endpoint will realize new payment for the same subscription as its parent payment.
     *
     * @see https://docs.thepay.eu/#tag/Subscriptions/paths/~1v2~1projects~1%7Bproject_id%7D~1payments~1%7Bparent_payment_uid%7D~1subscription~1irregular/post
     *
     * @param Identifier $parentPaymentUid UID of payment which initialized this subscription.
     *
     * @throws NotFoundApiException|ApiExceptionInterface
     */
    public function realizeIrregularSubscriptionPayment(Identifier $parentPaymentUid, RealizeIrregularSubscriptionPaymentParams $params): RecurringPaymentResult;

    /**
     * This endpoint will realize new payment for the same subscription as its parent payment with changeable price for different amount of shipped products or provided services.
     *
     * @see https://docs.thepay.eu/#tag/Subscriptions/paths/~1v2~1projects~1%7Bproject_id%7D~1payments~1%7Bparent_payment_uid%7D~1subscription~1usagebased/post
     *
     * @param Identifier $parentPaymentUid UID of payment which initialized this subscription.
     *
     * @throws NotFoundApiException|ApiExceptionInterface
     */
    public function realizeUsageBasedSubscriptionPayment(Identifier $parentPaymentUid, RealizeUsageBasedSubscriptionPaymentParams $params): RecurringPaymentResult;

    /**
     * This endpoint will realize a new payment by the same authorization which is saved in the parent payment.
     *
     * @see https://docs.thepay.eu/#tag/Saved-Card-Authorization/paths/~1v2~1projects~1%7Bproject_id%7D~1payments~1%7Bparent_payment_uid%7D~1savedauthorization/post
     *
     * @param Identifier $parentPaymentUid UID of first payment created with save_authorization=true.
     *
     * @throws NotFoundApiException|ApiExceptionInterface
     */
    public function realizePaymentBySavedAuthorization(Identifier $parentPaymentUid, RealizePaymentBySavedAuthorizationParams $params): RecurringPaymentResult;

    /**
     * Returns a list of payments belonging to a particular project.
     *
     * @see https://docs.thepay.eu/#tag/Payments/paths/~1v1~1projects~1%7Bproject_id%7D~1payments/get
     *
     * @param int<1, max> $page
     * @param int<1, 1000> $limit
     *
     * @throws NotFoundApiException|ApiExceptionInterface
     */
    public function getPayments(PaymentsFilter $filter, int $page = 1, int $limit = 25): PaymentCollection;

    /**
     * @see https://docs.thepay.eu/#tag/Transactions/paths/~1v1~1balances/get
     *
     * @param int|null $projectId
     *
     * @return array<AccountBalance>
     *
     * @throws NotFoundApiException|ApiExceptionInterface
     */
    public function getAccountsBalances(?StringValue $accountIban = null, $projectId = null, ?\DateTime $balanceAt = null);

    /**
     * @see https://docs.thepay.eu/#tag/Transactions/paths/~1v1~1transactions~1%7Baccount_iban%7D~1/get
     *
     * @param int<1, max> $page
     * @param int<1, 1000> $limit
     *
     * @throws NotFoundApiException|ApiExceptionInterface
     */
    public function getAccountTransactionHistory(TransactionFilter $filter, int $page = 1, int $limit = 100): TransactionCollection;

    /**
     * Returns an account statement for the specified account and time period in GPC format.
     *
     * @see https://docs.thepay.eu/#tag/Transactions/paths/~1v1~1transactions~1%7Baccount_iban%7D~1account_statement~1gpc/get
     *
     * @throws NotFoundApiException|ApiExceptionInterface
     */
    public function getAccountStatementGPC(TransactionFilter $filter, ?GPCPaymentIdentifier $paymentIdentifier = null): StreamInterface;

    /**
     * Creates new payment instance and returns URL to the payment process and URL to the payment detail page.
     *
     * @see https://docs.thepay.eu/#tag/Payment-Creation/paths/~1v1~1projects~1%7Bproject_id%7D~1payments/post
     *
     * @param non-empty-string|null $methodCode
     *
     * @throws ApiExceptionInterface
     */
    public function createPayment(CreatePaymentParams $createPaymentParams, ?string $methodCode = null): CreatePaymentResponse;

    /**
     * This endpoint will create request for realization of preauthorized payment.
     *
     * @see https://docs.thepay.eu/#tag/Preauthorized-Payments/paths/~1v2~1projects~1%7Bproject_id%7D~1payments~1%7Bpayment_uid%7D~1preauthorized/post
     *
     * @throws NotFoundApiException|ApiExceptionInterface
     */
    public function realizePreauthorizedPayment(RealizePreauthorizedPaymentParams $params): RealizePreauthorizedPaymentResult;

    /**
     * This endpoint will cancel preauthorized payment.
     *
     * @see https://docs.thepay.eu/#tag/Preauthorized-Payments/paths/~1v1~1projects~1%7Bproject_id%7D~1payments~1%7Bpayment_uid%7D~1preauthorized/delete
     *
     * @throws NotFoundApiException|ApiExceptionInterface
     */
    public function cancelPreauthorizedPayment(Identifier $uid): void;

    /**
     * This endpoint will change the current payment method, with which the payment can be paid.
     *
     * @see https://docs.thepay.eu/#tag/General-Payment-Management/paths/~1v1~1projects~1%7Bproject_id%7D~1payments~1%7Bpayment_uid%7D~1method/put
     *
     * @param non-empty-string $methodCode
     *
     * @throws NotFoundApiException|ApiExceptionInterface
     */
    public function changePaymentMethod(Identifier $uid, string $methodCode): void;

    /**
     * Returns information about payment refund.
     *
     * @see https://docs.thepay.eu/#tag/Refunds/paths/~1v1~1projects~1%7Bproject_id%7D~1payments~1%7Bpayment_uid%7D~1refund/get
     *
     * @return PaymentRefundInfo
     *
     * @throws NotFoundApiException|ApiExceptionInterface
     */
    public function getPaymentRefund(Identifier $uid);

    /**
     * This endpoint will create a request for automatic refund of a payment.
     *
     * @see https://docs.thepay.eu/#tag/Refunds/paths/~1v1~1projects~1%7Bproject_id%7D~1payments~1%7Bpayment_uid%7D~1refund/post
     *
     * @param Amount $amount amount which should be refunded in cents (currency used for refunding is same as payment currency)
     *
     * @throws NotFoundApiException|ApiExceptionInterface
     */
    public function createPaymentRefund(Identifier $uid, Amount $amount, string $reason): void;

    /**
     * Returns an array of available payment methods with pay URLs for certain payment.
     *
     * @see https://docs.thepay.eu/#tag/General-Payment-Management/paths/~1v1~1projects~1%7Bproject_id%7D~1payments~1%7Bpayment_uid%7D~1payment_urls/get
     *
     * @return array<PaymentMethodWithPayUrl>
     *
     * @throws NotFoundApiException|ApiExceptionInterface
     */
    public function getPaymentUrlsForPayment(Identifier $uid, ?LanguageCode $languageCode = null);

    /**
     * This endpoint will generate a PDF confirmation of paid payment specified by payment_uid.
     *
     * @see https://docs.thepay.eu/#tag/Payments/paths/~1v1~1projects~1%7Bproject_id%7D~1payments~1%7Bpayment_uid%7D~1generate_confirmation/get
     *
     * @return string with binary content of PDF file
     *
     * @throws NotFoundApiException|ApiExceptionInterface throws if payment is not paid yet
     */
    public function generatePaymentConfirmationPdf(Identifier $uid, ?LanguageCode $languageCode = null): string;
}
