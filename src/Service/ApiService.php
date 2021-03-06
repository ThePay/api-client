<?php

namespace ThePay\ApiClient\Service;

use ThePay\ApiClient\Exception\ApiException;
use ThePay\ApiClient\Filter\PaymentsFilter;
use ThePay\ApiClient\Filter\TransactionFilter;
use ThePay\ApiClient\Http\HttpResponse;
use ThePay\ApiClient\Http\HttpServiceInterface;
use ThePay\ApiClient\Model\ApiResponse;
use ThePay\ApiClient\Model\Collection\PaymentCollection;
use ThePay\ApiClient\Model\Collection\PaymentMethodCollection;
use ThePay\ApiClient\Model\Collection\TransactionCollection;
use ThePay\ApiClient\Model\CreatePaymentParams;
use ThePay\ApiClient\Model\CreatePaymentResponse;
use ThePay\ApiClient\Model\PaginatedCollectionParams;
use ThePay\ApiClient\Model\Payment;
use ThePay\ApiClient\Model\PaymentRefund;
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
use ThePay\ApiClient\Utils\Json;
use ThePay\ApiClient\ValueObject\Amount;
use ThePay\ApiClient\ValueObject\Identifier;
use ThePay\ApiClient\ValueObject\LanguageCode;
use ThePay\ApiClient\ValueObject\PaymentMethodCode;
use ThePay\ApiClient\ValueObject\StringValue;
use ThePay\ApiClient\ValueObject\SubscriptionType;

/**
 * Class ApiService is standard implementation for communication with ThePay Rest API
 *
 */
class ApiService implements ApiServiceInterface
{
    /** @var TheConfig */
    private $config;

    /** @var HttpServiceInterface */
    private $httpService;

    public function __construct(TheConfig $config, HttpServiceInterface $httpService)
    {
        $this->config = $config;
        $this->httpService = $httpService;
    }

    /**
     * Fetch all projects for merchant set in TheConfig
     *
     * @see https://dataapi21.docs.apiary.io/#reference/0/merchant-level-resources/get-projects
     *
     * @return Project[]
     */
    public function getProjects()
    {
        $url = $this->config->getApiUrl() . 'projects?merchant_id=' . $this->config->getMerchantId();
        $response = $this->httpService->get($url);

        if ($response->getCode() !== 200) {
            throw $this->buildException($url, $response);
        }

        $projects = array();
        foreach (json_decode($response->getBody(), true) as $item) {
            $projects[] = new Project(
                $item['project_id'],
                $item['project_url'],
                $item['account_iban']
            );
        }

        return $projects;
    }

    /**
     * Fetch all active payment methods.
     *
     * @see https://dataapi21.docs.apiary.io/#reference/projects/payment-methods/list-payment-methods
     * @param LanguageCode|null $languageCode language for payment method titles, null value language from TheConfig used
     * @return PaymentMethodCollection
     * @throws ApiException
     */
    public function getActivePaymentMethods(LanguageCode $languageCode = null)
    {
        $arguments = array();
        if ($languageCode) {
            $arguments['language'] = $languageCode->getValue();
        }

        $url = $this->url(array('methods'));
        $response = $this
            ->httpService
            ->get($url);

        if ($response->getCode() !== 200) {
            throw $this->buildException($url, $response);
        }

        return new PaymentMethodCollection($response->getBody());
    }

    /**
     * @see https://dataapi21.docs.apiary.io/#reference/0/merchant-level-resources/get-account-transaction-history
     *
     * @param TransactionFilter $filter
     * @param int $page
     * @param int $limit
     * @return TransactionCollection<SimpleTransaction>
     * @throws \Exception
     */
    public function getAccountTransactionHistory(TransactionFilter $filter, $page = 1, $limit = 100)
    {
        $paginatedCollectionParams = new PaginatedCollectionParams($filter, $page, $limit);

        $url = $this->url(array('transactions', $filter->getAccountIban()), $paginatedCollectionParams->toArray(), false);
        $response = $this
            ->httpService
            ->get($url);

        if ($response->getCode() !== 200) {
            throw $this->buildException($url, $response);
        }

        $headers = $response->getHeaders();

        return new TransactionCollection(Json::decode($response->getBody(), true), $page, $limit, (int) $headers['X-Total-Count:']);
    }

    /**
     * Get complete information about the specified payment.
     *
     * @see https://dataapi21.docs.apiary.io/#reference/0/project-level-resources/get-payment-detail
     *
     * @param Identifier $paymentUid
     *
     * @return Payment
     * @throws ApiException
     */
    public function getPayment(Identifier $paymentUid)
    {
        $url = $this->url(array('payments', $paymentUid));
        $response = $this
            ->httpService
            ->get($url);

        if ($response->getCode() !== 200) {
            throw $this->buildException($url, $response);
        }

        return new Payment($response->getBody());
    }

    /**
     * Invalidates the specified payment.
     *
     * @see https://dataapi21.docs.apiary.io/#reference/0/project-level-resources/invalidate-payment
     *
     * @param Identifier $paymentUid
     *
     * @return void
     * @throws ApiException
     */
    public function invalidatePayment(Identifier $paymentUid)
    {
        $url = $this->url(array('payments', $paymentUid, 'invalidate'));
        $response = $this
            ->httpService
            ->put($url);

        if ($response->getCode() !== 200) {
            throw $this->buildException($url, $response);
        }
    }

    /**
     * Fetch information about payments by filter.
     *
     * @see https://dataapi21.docs.apiary.io/#reference/projects/payments/get-collection
     * @param PaymentsFilter $filter
     * @param int $page
     * @param null|int $limit
     * @return PaymentCollection<SimplePayment>
     * @throws ApiException
     */
    public function getPayments(PaymentsFilter $filter, $page = 1, $limit = 25)
    {
        $paginatedCollectionParams = new PaginatedCollectionParams($filter, $page, $limit);

        $url = $this->url(array('payments'), $paginatedCollectionParams->toArray());
        $response = $this
            ->httpService
            ->get($url);

        if ($response->getCode() !== 200) {
            throw $this->buildException($url, $response);
        }

        $headers = $response->getHeaders();

        return new PaymentCollection($response->getBody(), $page, $limit, (int) $headers['X-Total-Count:']);
    }

    /**
     * @param Identifier $parentPaymentUid UID of payment which initialized this subscription.
     * @param RealizeRegularSubscriptionPaymentParams $params
     * @return ApiResponse
     * @throws ApiException
     */
    public function realizeRegularSubscriptionPayment(Identifier $parentPaymentUid, RealizeRegularSubscriptionPaymentParams $params)
    {
        $jsonParams = $params->toArray();

        $url = $this->url(array('payments', $parentPaymentUid, 'subscription', SubscriptionType::REGULAR));
        $response = $this
            ->httpService
            ->post($url, json_encode($jsonParams));

        if ( ! in_array($response->getCode(), array(200, 201), true)) {
            throw $this->buildException($url, $response);
        }

        return new ApiResponse($response->getBody(), $response->getCode());
    }

    /**
     * @param Identifier $parentPaymentUid UID of payment which initialized this subscription.
     * @param RealizeIrregularSubscriptionPaymentParams $params
     * @return ApiResponse
     * @throws ApiException
     */
    public function realizeIrregularSubscriptionPayment(Identifier $parentPaymentUid, RealizeIrregularSubscriptionPaymentParams $params)
    {
        $jsonParams = $params->toArray();

        $url = $this->url(array('payments', $parentPaymentUid, 'subscription', SubscriptionType::IRREGULAR));
        $response = $this
            ->httpService
            ->post($url, json_encode($jsonParams));

        if ( ! in_array($response->getCode(), array(200, 201), true)) {
            throw $this->buildException($url, $response);
        }

        return new ApiResponse($response->getBody(), $response->getCode());
    }

    /**
     * @param Identifier $parentPaymentUid UID of payment which initialized this subscription.
     * @param RealizeUsageBasedSubscriptionPaymentParams $params
     * @return ApiResponse
     * @throws ApiException
     */
    public function realizeUsageBasedSubscriptionPayment(Identifier $parentPaymentUid, RealizeUsageBasedSubscriptionPaymentParams $params)
    {
        $jsonParams = $params->toArray();

        $url = $this->url(array('payments', $parentPaymentUid, 'subscription', SubscriptionType::USAGE_BASED));
        $response = $this
            ->httpService
            ->post($url, json_encode($jsonParams));

        if ( ! in_array($response->getCode(), array(200, 201), true)) {
            throw $this->buildException($url, $response);
        }

        return new ApiResponse($response->getBody(), $response->getCode());
    }

    /**
     * @param Identifier $parentPaymentUid UID of first payment created with save_authorization=true.
     * @param RealizePaymentBySavedAuthorizationParams $params
     * @return ApiResponse
     * @throws ApiException
     */
    public function realizePaymentBySavedAuthorization(Identifier $parentPaymentUid, RealizePaymentBySavedAuthorizationParams $params)
    {
        $jsonParams = $params->toArray();

        $url = $this->url(array('payments', $parentPaymentUid, 'savedauthorization'));
        $response = $this
            ->httpService
            ->post($url, json_encode($jsonParams));

        if ( ! in_array($response->getCode(), array(200, 201), true)) {
            throw $this->buildException($url, $response);
        }

        return new ApiResponse($response->getBody(), $response->getCode());
    }


    /**
     * @return CreatePaymentResponse
     * @throws ApiException
     */
    public function createPayment(CreatePaymentParams $createPaymentParams, PaymentMethodCode $paymentMethod = null)
    {
        $jsonParams = $createPaymentParams->toArray();
        if ($paymentMethod) {
            $jsonParams['payment_method_code'] = $paymentMethod->getValue();
        }

        $url = $this->url(array('payments'));
        $response = $this
            ->httpService
            ->post($url, json_encode($jsonParams));

        if ( ! in_array($response->getCode(), array(200, 201), true)) {
            throw $this->buildException($url, $response);
        }

        return new CreatePaymentResponse($response->getBody(), $response->getCode() === 201);
    }

    /**
     * @throws ApiException
     * @return bool
     */
    public function changePaymentMethod(Identifier $uid, PaymentMethodCode $paymentMethodCode)
    {
        $url = $this->url(array('payments', $uid, 'method'));
        $response = $this
            ->httpService
            ->put($url, json_encode(array(
                'payment_method_code' => $paymentMethodCode->getValue(),
            )));

        if ($response->getCode() !== 204) {
            throw $this->buildException($url, $response);
        }

        return true;
    }

    /**
     * @param RealizePreauthorizedPaymentParams $realizePreauthorizedPaymentParams
     * @throws ApiException
     * @return bool
     */
    public function realizePreauthorizedPayment(RealizePreauthorizedPaymentParams $realizePreauthorizedPaymentParams)
    {
        $url = $this->url(array(
            'payments',
            $realizePreauthorizedPaymentParams->getUid(),
            'preauthorized',
        ));
        $response = $this
            ->httpService
            ->post($url, json_encode($realizePreauthorizedPaymentParams->toArray()));

        if ($response->getCode() !== 204) {
            throw $this->buildException($url, $response);
        }

        return true;
    }

    /**
     * @param Identifier $uid
     * @throws ApiException
     * @return bool
     */
    public function cancelPreauthorizedPayment(Identifier $uid)
    {
        $url = $this->url(array('payments', $uid, 'preauthorized'));
        $response = $this
            ->httpService
            ->delete($url);

        if ($response->getCode() !== 204) {
            throw $this->buildException($url, $response);
        }

        return true;
    }

    /**
     * Returns information about payment refund.
     *
     * @return PaymentRefundInfo
     */
    public function getPaymentRefund(Identifier $uid)
    {
        $url = $this->url(array('payments', $uid->getValue(), 'refund'));
        $response = $this->httpService->get($url);
        if ($response->getCode() !== 200) {
            throw $this->buildException($url, $response);
        }

        $responseData = Json::decode($response->getBody());

        $refunds = array();
        foreach ($responseData->partial_refunds as $responseRefund) {
            $refunds[] = new PaymentRefund($responseRefund->amount, $responseRefund->reason, $responseRefund->state);
        }

        return new PaymentRefundInfo($responseData->available_amount, $responseData->currency, $refunds);
    }

    /**
     * Will create request for automatic refund of payment.
     *
     * @param Amount $amount amount which should be refunded in cents (currency used for refunding is same as payment currency)
     * @return void
     */
    public function createPaymentRefund(Identifier $uid, Amount $amount, StringValue $reason)
    {
        $url = $this->url(array('payments', $uid->getValue(), 'refund'));
        $response = $this->httpService->post($url, json_encode(array(
            'amount' => $amount->getValue(),
            'reason' => $reason->getValue(),
        )));

        if ($response->getCode() !== 201) {
            throw $this->buildException($url, $response);
        }
    }


    /**
     * Build URL for API requests
     *
     * @param array<string> $path
     * @param array<string, mixed> $arguments
     * @param bool $includeProject
     * @return string
     */
    private function url($path = array(), $arguments = array(), $includeProject = true)
    {
        if ( ! isset($arguments['merchant_id'])) {
            ($arguments['merchant_id'] = $this->config->getMerchantId());
        }
        if ( ! isset($arguments['language'])) {
            ($arguments['language'] = $this->config->getLanguage());
        }

        if ($includeProject) {
            // Specify project
            array_unshift($path, 'projects', $this->config->getProjectId());
        }

        $pathImploded = implode('/', $path);
        if (strlen($pathImploded)) {
            $pathImploded .= '/';
        }

        $apiUrl = $this->config->getApiUrl();

        $pathImploded = substr($pathImploded, 0, -1);
        $argsPath = '';

        if ($arguments) {
            $argsPath .= '?' . http_build_query($arguments);
        }

        return $apiUrl . $pathImploded . $argsPath;
    }

    /**
     * @param string $requestUrl
     * @param HttpResponse $response
     * @return \Exception
     */
    private function buildException($requestUrl, HttpResponse $response)
    {
        $responseCode = (int) $response->getCode();
        $message = 'TheApi call "' . $requestUrl . '" failed, status code: ' . $responseCode . ' ' . $response->getCodeMessage();
        $message .= $this->getErrorResponseMessage((string) $response->getBody());

        if ($responseCode == 0 || $responseCode >= 500) {
            return new ApiException($message, $responseCode);
        }

        return new \RuntimeException($message, $responseCode);
    }

    /**
     * @param string $bodyString
     * @return string
     */
    private function getErrorResponseMessage($bodyString)
    {
        if ($bodyString == '') {
            return '';
        }

        $bodyData = json_decode($bodyString);
        if (
            is_object($bodyData)
            &&
            property_exists($bodyData, 'message')
        ) {
            return ' message: "' . $bodyData->message . '"';
        }

        return ' invalid error response format body: "' . $bodyString . '"';
    }
}
