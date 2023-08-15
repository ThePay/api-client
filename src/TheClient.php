<?php

namespace ThePay\ApiClient;

use Exception;
use InvalidArgumentException;
use ThePay\ApiClient\Exception\ApiException;
use ThePay\ApiClient\Filter\PaymentMethodFilter;
use ThePay\ApiClient\Filter\PaymentsFilter;
use ThePay\ApiClient\Filter\TransactionFilter;
use ThePay\ApiClient\Http\HttpCurlService;
use ThePay\ApiClient\Http\HttpServiceInterface;
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
use ThePay\ApiClient\Service\ApiService;
use ThePay\ApiClient\Service\ApiServiceInterface;
use ThePay\ApiClient\Service\GateService;
use ThePay\ApiClient\Service\GateServiceInterface;
use ThePay\ApiClient\Service\SignatureService;
use ThePay\ApiClient\ValueObject\Amount;
use ThePay\ApiClient\ValueObject\Identifier;
use ThePay\ApiClient\ValueObject\LanguageCode;
use ThePay\ApiClient\ValueObject\PaymentMethodCode;
use ThePay\ApiClient\ValueObject\StringValue;

/**
 * Class ThePay is base class for ThePay SDK
 * @package ThePay\ApiClient
 */
class TheClient
{
    /** @var string */
    const VERSION = '1.7.0';

    /** @var TheConfig */
    private $config;

    /** @var GateServiceInterface */
    private $gate;

    /** @var ApiServiceInterface */
    private $api;

    /** @var HttpServiceInterface */
    private $http;

    public function __construct(
        TheConfig $config,
        GateServiceInterface $gate = null,
        HttpServiceInterface $http = null,
        ApiServiceInterface $api = null
    ) {
        $this->config = $config;
        $this->http = $http ?: new HttpCurlService(new SignatureService($config));
        $this->api = $api ?: new ApiService($config, $this->http);
        $this->gate = $gate ?: new GateService($config, $this->api);
    }

    /**
     * Fetch all projects for merchant set in TheConfig
     *
     * @see https://dataapi21.docs.apiary.io/#reference/0/merchant-level-resources/get-projects
     *
     * @return Project[]
     * @throws ApiException
     */
    public function getProjects()
    {
        return $this->api->getProjects();
    }

    /**
     * @see https://dataapi21.docs.apiary.io/#reference/data-retrieval/transactions/get-balance-history
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
                array($params->getCurrencyCode()->getValue()),
                array(),
                array()
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
    public function getPaymentButton(CreatePaymentParams $params, $title = 'Pay!', $useInlineAssets = true, $methodCode = null, array $attributes = array(), $usePostMethod = true)
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
     * @param CreatePaymentParams $params
     * @param string|null $methodCode
     *
     * @return CreatePaymentResponse
     * @throws ApiException
     */
    public function createPayment(CreatePaymentParams $params, $methodCode = null)
    {
        if ($params->getLanguageCode() === null) {
            $params->setLanguageCode($this->config->getLanguage()->getValue());
        }

        $paymentMethod = $methodCode === null ? null : new PaymentMethodCode($methodCode);

        return $this
            ->api
            ->createPayment($params, $paymentMethod);
    }

    /**
     * @param string $paymentUid
     * @param string $paymentMethodCode
     * @return bool
     * @throws ApiException|InvalidArgumentException
     */
    public function changePaymentMethod($paymentUid, $paymentMethodCode)
    {
        $this->validateUid($paymentUid);
        return $this
            ->api
            ->changePaymentMethod(new Identifier($paymentUid), new PaymentMethodCode($paymentMethodCode));
    }

    /**
     * @param RealizePreauthorizedPaymentParams $params
     * @return bool
     * @throws ApiException|InvalidArgumentException
     */
    public function realizePreauthorizedPayment(RealizePreauthorizedPaymentParams $params)
    {
        $this->validateUid($params->getUid()->getValue());
        return $this
            ->api
            ->realizePreauthorizedPayment($params);
    }

    /**
     * @param string $paymentUid
     * @return bool
     * @throws ApiException|InvalidArgumentException
     */
    public function cancelPreauthorizedPayment($paymentUid)
    {
        $this->validateUid($paymentUid);
        return $this
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
        $this->api->createPaymentRefund(new Identifier($paymentUid), new Amount($amount), new StringValue($reason));
    }

    /**
     * Method will generate PDF file as confirmation for paid payment
     *
     * @see https://dataapi21.docs.apiary.io/#reference/data-retrieval/payments/get-payment-confirmation
     *
     * @param non-empty-string $paymentUid
     * @param non-empty-string|null $languageCode
     *
     * @return string with binary content of PDF file
     *
     * @throws ApiException if payment is not paid yet
     */
    public function generatePaymentConfirmationPdf($paymentUid, $languageCode = null)
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
