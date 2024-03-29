<?php

namespace ThePay\ApiClient;

use Exception;
use InvalidArgumentException;
use ThePay\ApiClient\Exception\ApiException;
use ThePay\ApiClient\Filter\PaymentMethodFilter;
use ThePay\ApiClient\Filter\PaymentsFilter;
use ThePay\ApiClient\Filter\TransactionFilter;
use ThePay\ApiClient\Model\AccountBalance;
use ThePay\ApiClient\Model\ApiResponse;
use ThePay\ApiClient\Model\Collection\PaymentCollection;
use ThePay\ApiClient\Model\Collection\PaymentMethodCollection;
use ThePay\ApiClient\Model\CreatePaymentParams;
use ThePay\ApiClient\Model\CreatePaymentResponse;
use ThePay\ApiClient\Model\PaymentMethodWithPayUrl;
use ThePay\ApiClient\Model\PaymentRefundInfo;
use ThePay\ApiClient\Model\Project;
use ThePay\ApiClient\Model\RealizeIrregularSubscriptionPaymentParams;
use ThePay\ApiClient\Model\RealizePaymentBySavedAuthorizationParams;
use ThePay\ApiClient\Model\RealizePreauthorizedPaymentParams;
use ThePay\ApiClient\Model\RealizeRegularSubscriptionPaymentParams;
use ThePay\ApiClient\Model\RealizeUsageBasedSubscriptionPaymentParams;
use ThePay\ApiClient\Model\SimplePayment;
use ThePay\ApiClient\Model\SimpleTransaction;
use ThePay\ApiClient\Service\ApiServiceInterface;
use ThePay\ApiClient\Service\GateService;
use ThePay\ApiClient\Service\GateServiceInterface;
use ThePay\ApiClient\ValueObject\Amount;
use ThePay\ApiClient\ValueObject\Identifier;
use ThePay\ApiClient\ValueObject\LanguageCode;
use ThePay\ApiClient\ValueObject\StringValue;

/**
 * Class ThePay is base class for ThePay SDK
 */
class TheClient
{
    /** @var string */
    public const VERSION = '2.0.0';

    private TheConfig $config;
    private GateServiceInterface $gate;
    private ApiServiceInterface $api;

    public function __construct(
        TheConfig $config,
        ApiServiceInterface $api,
        ?GateServiceInterface $gate = null
    ) {
        $this->config = $config;
        $this->api = $api;
        $this->gate = $gate ?? new GateService($config, $api);
    }

    /**
     * Fetch all projects for merchant set in TheConfig
     *
     * @see https://thepay.docs.apiary.io/#reference/0/merchant-level-resources/get-projects
     *
     * @return Project[]
     * @throws ApiException
     */
    public function getProjects()
    {
        return $this->api->getProjects();
    }

    /**
     * @see https://thepay.docs.apiary.io/#reference/data-retrieval/transactions/get-balance-history
     *
     * @param string|null $accountIban
     * @param int|null $projectId
     *
     * @return array<AccountBalance>
     */
    public function getAccountsBalances($accountIban = null, $projectId = null, \DateTime $balanceAt = null)
    {
        return $this->api->getAccountsBalances(
            $accountIban !== null ? new StringValue($accountIban) : null,
            $projectId,
            $balanceAt
        );
    }

    /**
     * @param TransactionFilter $filter
     * @param int $page
     * @param int $limit
     * @return Model\Collection\TransactionCollection<SimpleTransaction>
     * @throws Exception
     */
    public function getAccountTransactionHistory(TransactionFilter $filter, $page = 1, $limit = 100)
    {
        return $this
            ->api
            ->getAccountTransactionHistory($filter, $page, $limit);
    }

    /**
     * @param PaymentMethodFilter|null $filter
     * @param LanguageCode|null $languageCode language for payment method titles, null value language from TheConfig used
     * @param bool $isRecurring
     * @param bool $isDeposit
     * @return PaymentMethodCollection
     * @throws ApiException
     */
    public function getActivePaymentMethods(PaymentMethodFilter $filter = null, LanguageCode $languageCode = null, $isRecurring = false, $isDeposit = true)
    {
        $paymentMethods = $this
                ->api
                ->getActivePaymentMethods($languageCode);

        if ($filter !== null) {
            return $paymentMethods->getFiltered($filter, $isRecurring, $isDeposit);
        }

        return $paymentMethods;
    }

    /**
     * @param string $paymentUid
     * @return Model\Payment
     * @throws ApiException|InvalidArgumentException
     */
    public function getPayment($paymentUid)
    {
        $this->validateUid($paymentUid);
        return $this
            ->api
            ->getPayment(new Identifier($paymentUid));
    }

    /**
     * @param string $paymentUid
     * @return void
     * @throws ApiException|InvalidArgumentException
     */
    public function invalidatePayment($paymentUid)
    {
        $this->validateUid($paymentUid);
        $this->api->invalidatePayment(new Identifier($paymentUid));
    }

    /**
     * @param string $parentPaymentUid UID of payment which initialized this subscription.
     * @param RealizeRegularSubscriptionPaymentParams $params
     * @return ApiResponse
     * @throws ApiException|InvalidArgumentException
     */
    public function realizeRegularSubscriptionPayment($parentPaymentUid, RealizeRegularSubscriptionPaymentParams $params)
    {
        $this->validateUid($parentPaymentUid);
        return $this->api->realizeRegularSubscriptionPayment(new Identifier($parentPaymentUid), $params);
    }

    /**
     * @param string $parentPaymentUid UID of payment which initialized this subscription.
     * @param RealizeIrregularSubscriptionPaymentParams $params
     * @return ApiResponse
     * @throws ApiException|InvalidArgumentException
     */
    public function realizeIrregularSubscriptionPayment($parentPaymentUid, RealizeIrregularSubscriptionPaymentParams $params)
    {
        $this->validateUid($parentPaymentUid);
        return $this->api->realizeIrregularSubscriptionPayment(new Identifier($parentPaymentUid), $params);
    }

    /**
     * @param string $parentPaymentUid UID of payment which initialized this subscription.
     * @param RealizeUsageBasedSubscriptionPaymentParams $params
     * @return ApiResponse
     * @throws ApiException|InvalidArgumentException
     */
    public function realizeUsageBasedSubscriptionPayment($parentPaymentUid, RealizeUsageBasedSubscriptionPaymentParams $params)
    {
        $this->validateUid($parentPaymentUid);
        return $this->api->realizeUsageBasedSubscriptionPayment(new Identifier($parentPaymentUid), $params);
    }

    /**
     * @param string $parentPaymentUid UID of first payment created with save_authorization=true.
     * @param RealizePaymentBySavedAuthorizationParams $params
     * @return ApiResponse
     * @throws ApiException|InvalidArgumentException
     */
    public function realizePaymentBySavedAuthorization($parentPaymentUid, RealizePaymentBySavedAuthorizationParams $params)
    {
        $this->validateUid($parentPaymentUid);
        return $this->api->realizePaymentBySavedAuthorization(new Identifier($parentPaymentUid), $params);
    }


    /**
     * @param PaymentsFilter $filter Associative array of filters
     * @param int $page
     * @param null|int $limit
     * @return PaymentCollection<SimplePayment>
     * @throws ApiException
     */
    public function getPayments(PaymentsFilter $filter = null, $page = 1, $limit = 25)
    {
        if ($filter === null) {
            $filter = new PaymentsFilter();
        }

        return $this
            ->api
            ->getPayments($filter, $page, $limit);
    }

    /**
     * Returns an array of available payment methods with pay URLs for certain payment.
     *
     * @param string $uid UID of payment,
     * @param string|null $languageCode language code in ISO 6391 format
     * @return array<PaymentMethodWithPayUrl>
     */
    public function getPaymentUrlsForPayment($uid, $languageCode = null)
    {
        $this->validateUid($uid);

        $language = null;
        if ($languageCode !== null) {
            $language = new LanguageCode($languageCode);
        }

        return $this
            ->api
            ->getPaymentUrlsForPayment(new Identifier($uid), $language);
    }

    /**
     * Returns HTML code with payment buttons for each available payment method.
     * Every button is a link with click event handler to post the user to the payment process.
     *
     * @param CreatePaymentParams      $params
     * @param PaymentMethodFilter|null $filter
     * @param bool $useInlineAssets false value disable generation default style & scripts
     * @return string
     * @throws ApiException
     */
    public function getPaymentButtons(CreatePaymentParams $params, PaymentMethodFilter $filter = null, $useInlineAssets = true)
    {
        $params = $this->setLanguageCodeIfMissing($params);

        if ($filter === null) {
            $filter = new PaymentMethodFilter(
                [$params->getCurrencyCode()->getValue()],
                [],
                []
            );
        } else {
            $filter->setCurrency($params->getCurrencyCode()->getValue());
        }

        $methods = $this->getActivePaymentMethods($filter, $params->getLanguageCode(), $params->getSaveAuthorization(), $params->isDeposit());

        $result = '';
        if ($useInlineAssets) {
            $result .= $this->getInlineAssets();
        }
        $result .= $this->gate->getPaymentButtons($params, $methods);
        return $result;
    }

    /**
     * Returns HTML code with payment buttons for each available payment method.
     * Every button contains direct link to pay with certain method.
     *
     * @param string $uid UID of payment
     * @param string|null $languageCode
     * @param bool $useInlineAssets false value disable generation default style & scripts
     *
     * @return string HTML
     */
    public function getPaymentButtonsForPayment($uid, $languageCode = null, $useInlineAssets = true)
    {
        $result = '';
        if ($useInlineAssets) {
            $result .= $this->getInlineAssets();
        }
        $result .= $this->gate->getPaymentButtonsForPayment(new Identifier($uid), $languageCode ? new LanguageCode($languageCode) : $languageCode);
        return $result;
    }

    /**
     * @param CreatePaymentParams $params
     * @param string $title
     * @param bool $useInlineAssets false value disable generation default style & scripts
     * @param string|null $methodCode
     * @param array<string, string> $attributes
     * @param bool $usePostMethod
     * @return string This is HTML snippet with link redirection to payment gate. To payment method selection page.
     */
    public function getPaymentButton(CreatePaymentParams $params, $title = 'Pay!', $useInlineAssets = true, $methodCode = null, array $attributes = [], $usePostMethod = true)
    {
        $this->setLanguageCodeIfMissing($params);

        $result = '';
        if ($useInlineAssets) {
            $result .= $this->getInlineAssets();
        }
        $result .= $this->gate->getPaymentButton(htmlspecialchars($title), $params, $methodCode, $attributes, $usePostMethod);
        return $result;
    }

    /**
     * @param non-empty-string|null $methodCode
     *
     * @throws ApiException
     */
    public function createPayment(CreatePaymentParams $params, ?string $methodCode = null): CreatePaymentResponse
    {
        if ($params->getLanguageCode() === null) {
            $params->setLanguageCode($this->config->getLanguage()->getValue());
        }

        return $this
            ->api
            ->createPayment($params, $methodCode);
    }

    /**
     * @param non-empty-string $paymentUid
     * @param non-empty-string $methodCode
     *
     * @throws ApiException|InvalidArgumentException
     */
    public function changePaymentMethod(string $paymentUid, string $methodCode): void
    {
        $this->validateUid($paymentUid);
        $this
            ->api
            ->changePaymentMethod(new Identifier($paymentUid), $methodCode);
    }

    /**
     * @param RealizePreauthorizedPaymentParams $params
     *
     * @throws ApiException|InvalidArgumentException
     */
    public function realizePreauthorizedPayment(RealizePreauthorizedPaymentParams $params): void
    {
        $this->validateUid($params->getUid()->getValue());
        $this
            ->api
            ->realizePreauthorizedPayment($params);
    }

    /**
     * @param non-empty-string $paymentUid
     *
     * @throws ApiException|InvalidArgumentException
     */
    public function cancelPreauthorizedPayment(string $paymentUid): void
    {
        $this->validateUid($paymentUid);
        $this
            ->api
            ->cancelPreauthorizedPayment(new Identifier($paymentUid));
    }

    /**
     * Returns information about payment refund.
     *
     * @param string $paymentUid
     * @return PaymentRefundInfo
     * @throws ApiException|InvalidArgumentException
     */
    public function getPaymentRefund($paymentUid)
    {
        $this->validateUid($paymentUid);
        return $this->api->getPaymentRefund(new Identifier($paymentUid));
    }

    /**
     * Will create request for automatic refund of payment.
     *
     * @param string $paymentUid
     * @param int $amount amount which should be refunded in cents (currency used for refunding is same as payment currency)
     * @param string $reason
     * @return void
     * @throws ApiException|InvalidArgumentException
     */
    public function createPaymentRefund($paymentUid, $amount, $reason)
    {
        $this->validateUid($paymentUid);
        $this->api->createPaymentRefund(new Identifier($paymentUid), new Amount($amount), $reason);
    }

    /**
     * Method will generate PDF file as confirmation for paid payment
     *
     * @see https://thepay.docs.apiary.io/#reference/data-retrieval/payments/get-payment-confirmation
     *
     * @param non-empty-string $paymentUid
     * @param non-empty-string|null $languageCode
     *
     * @return string with binary content of PDF file
     *
     * @throws ApiException if payment is not paid yet
     */
    public function generatePaymentConfirmationPdf(string $paymentUid, string $languageCode = null): string
    {
        $this->validateUid($paymentUid);
        return $this->api->generatePaymentConfirmationPdf(
            new Identifier($paymentUid),
            $languageCode !== null ? new LanguageCode($languageCode) : null
        );
    }

    /**
     * Returns <style> and <script> tags with styles and javascript code
     * @return string HTML code
     */
    public function getInlineAssets()
    {
        return $this->gate->getInlineAssets();
    }

    /**
     * Returns <style> tag with inline styles
     * @return string HTML code
     */
    public function getInlineStyles()
    {
        return $this->gate->getInlineStyles();
    }

    /**
     * Returns <script> tag with javascript code
     * @return string HTML code
     */
    public function getInlineScripts()
    {
        return $this->gate->getInlineScripts();
    }

    /**
     * @param CreatePaymentParams $params
     * @return CreatePaymentParams
     */
    private function setLanguageCodeIfMissing(CreatePaymentParams $params)
    {
        if ($params->getLanguageCode() === null) {
            $params->setLanguageCode($this->config->getLanguage()->getValue());
        }

        return $params;
    }

    /**
     * Validate that $uid is not empty
     * @param mixed $uid
     * @return void
     * @throws InvalidArgumentException if $uid is null or ''
     */
    private function validateUid($uid)
    {
        if ($uid === null) {
            throw new InvalidArgumentException('Payment UID cannot be null.');
        }
        if ($uid === '') {
            throw new InvalidArgumentException('Payment UID cannot be empty string.');
        }
        if ( ! is_scalar($uid) && ( ! is_object($uid) || ! method_exists($uid, '__toString'))) {
            throw new InvalidArgumentException('Payment UID cannot be converted to string.');
        }
    }
}
