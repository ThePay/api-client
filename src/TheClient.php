<?php

namespace ThePay\ApiClient;

use ThePay\ApiClient\Exception\ApiException;
use ThePay\ApiClient\Filter\PaymentMethodFilter;
use ThePay\ApiClient\Filter\PaymentsFilter;
use ThePay\ApiClient\Http\HttpCurlService;
use ThePay\ApiClient\Http\HttpServiceInterface;
use ThePay\ApiClient\Model\Collection\PaymentCollection;
use ThePay\ApiClient\Model\Collection\PaymentMethodCollection;
use ThePay\ApiClient\Model\CreatePaymentParams;
use ThePay\ApiClient\Model\CreatePaymentResponse;
use ThePay\ApiClient\Model\PaymentRefundInfo;
use ThePay\ApiClient\Model\RealizePreauthorizedPaymentParams;
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
    const VERSION = '1.0.0';

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
     * @throws ApiException
     */
    public function getPayment($paymentUid)
    {
        return $this
            ->api
            ->getPayment(Identifier::create($paymentUid));
    }

    /**
     * @param PaymentsFilter $filter Associative array of filters
     * @param int $page
     * @param null|int $limit
     * @return PaymentCollection
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

        $methods = $this->getActivePaymentMethods($filter, $params->getLanguageCode(), $params->isRecurring(), $params->isDeposit());

        $result = '';
        if ($useInlineAssets) {
            $result .= $this->getInlineAssets();
        }
        $result .= $this->gate->getPaymentButtons($params, $methods);
        return $result;
    }

    /**
     * @param CreatePaymentParams $params
     * @param string $title
     * @param bool $useInlineAssets false value disable generation default style & scripts
     * @param string|null $methodCode
     * @param array $attributes
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
     *
     * @return CreatePaymentResponse
     * @throws ApiException
     */
    public function createPayment(CreatePaymentParams $params)
    {
        if ($params->getLanguageCode() === null) {
            $params->setLanguageCode($this->config->getLanguage()->getValue());
        }

        return $this
            ->api
            ->createPayment($params);
    }

    /**
     * @param string $uid
     * @param string $paymentMethodCode
     * @throws ApiException
     * @return bool
     */
    public function changePaymentMethod($uid, $paymentMethodCode)
    {
        return $this
            ->api
            ->changePaymentMethod(new Identifier($uid), new PaymentMethodCode($paymentMethodCode));
    }

    /**
     * @param RealizePreauthorizedPaymentParams $params
     * @throws ApiException
     * @return bool
     */
    public function realizePreauthorizedPayment(RealizePreauthorizedPaymentParams $params)
    {
        return $this
            ->api
            ->realizePreauthorizedPayment($params);
    }

    /**
     * @param string $uid
     * @throws ApiException
     * @return bool
     */
    public function cancelPreauthorizedPayment($uid)
    {
        return $this
            ->api
            ->cancelPreauthorizedPayment(new Identifier($uid));
    }

    /**
     * Returns information about payment refund.
     *
     * @param string $uid
     * @return PaymentRefundInfo
     */
    public function getPaymentRefund($uid)
    {
        return $this->api->getPaymentRefund(new Identifier($uid));
    }

    /**
     * Will create request for automatic refund of payment.
     *
     * @param string $uid
     * @param int $amount amount which should be refunded in cents (currency used for refunding is same as payment currency)
     * @param string $reason
     * @return void
     */
    public function createPaymentRefund($uid, $amount, $reason)
    {
        $this->api->createPaymentRefund(new Identifier($uid), new Amount($amount), new StringValue($reason));
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
}
