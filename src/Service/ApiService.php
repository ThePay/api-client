<?php

namespace ThePay\ApiClient\Service;

use ThePay\ApiClient\Exception\ApiException;
use ThePay\ApiClient\Filter\PaymentsFilter;
use ThePay\ApiClient\Http\HttpResponse;
use ThePay\ApiClient\Http\HttpServiceInterface;
use ThePay\ApiClient\Model\Collection\PaymentCollection;
use ThePay\ApiClient\Model\Collection\PaymentMethodCollection;
use ThePay\ApiClient\Model\CreatePaymentParams;
use ThePay\ApiClient\Model\CreatePaymentResponse;
use ThePay\ApiClient\Model\CreateRecurringPaymentParams;
use ThePay\ApiClient\Model\PaginatedCollectionParams;
use ThePay\ApiClient\Model\Payment;
use ThePay\ApiClient\Model\PaymentMethod;
use ThePay\ApiClient\Model\PaymentRefund;
use ThePay\ApiClient\Model\PaymentRefundInfo;
use ThePay\ApiClient\Model\RealizePreauthorizedPaymentParams;
use ThePay\ApiClient\Model\RealizeRecurringPaymentResponse;
use ThePay\ApiClient\TheConfig;
use ThePay\ApiClient\Utils\Json;
use ThePay\ApiClient\ValueObject\Amount;
use ThePay\ApiClient\ValueObject\Identifier;
use ThePay\ApiClient\ValueObject\LanguageCode;
use ThePay\ApiClient\ValueObject\StringValue;

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
     * Get complete information about the specified payment.
     *
     * @see https://dataapi21.docs.apiary.io/#reference/projects/payment/get-specific-payment
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
     * Fetch information about payments by filter.
     *
     * @see https://dataapi21.docs.apiary.io/#reference/projects/payments/get-collection
     * @param PaymentsFilter $filter
     * @param int $page
     * @param null|int $limit
     * @return PaymentCollection
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

        return new PaymentCollection($response->getBody(), $page, $limit, $headers['X-Total-Count:']);
    }

    /**
     * @return CreatePaymentResponse
     * @throws ApiException
     */
    public function createPayment(CreatePaymentParams $createPaymentParams, PaymentMethod $paymentMethod = null)
    {
        $jsonParams = $createPaymentParams->toArray();
        if ($paymentMethod) {
            $jsonParams['payment_method_code'] = $paymentMethod->getCode();
        }

        $url = $this->url(array('payments'));
        $response = $this
            ->httpService
            ->post($url, json_encode($jsonParams));

        if ($response->getCode() !== 201) {
            throw $this->buildException($url, $response);
        }

        return new CreatePaymentResponse($response->getBody());
    }

    /**
     * @throws ApiException
     * @return bool
     */
    public function changePaymentMethod(Identifier $uid, PaymentMethod $paymentMethod)
    {
        $url = $this->url(array('payments', $uid, 'method'));
        $response = $this
            ->httpService
            ->put($url, json_encode([
                'payment_method_code' => $paymentMethod->getCode()
            ]));

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
            'preauthorized'
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
     * @param CreateRecurringPaymentParams $params
     *
     * @return RealizeRecurringPaymentResponse
     * @throws ApiException
     */
    public function realizeRecurringPayment(CreateRecurringPaymentParams $params)
    {
        $url = $this->url(array('payments', $params->getParentUid(), 'recurring'));
        $response = $this
            ->httpService
            ->post($url, json_encode($params->toArray()));

        if ($response->getCode() !== 200) {
            throw $this->buildException($url, $response);
        }

        return new RealizeRecurringPaymentResponse($response->getBody());
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
     * @param array $path
     * @param array $arguments
     * @return string
     */
    private function url($path = array(), $arguments = array())
    {
        if ( ! isset($arguments['merchant_id'])) {
            ($arguments['merchant_id'] = $this->config->getMerchantId());
        }
        if ( ! isset($arguments['language'])) {
            ($arguments['language'] = $this->config->getLanguage());
        }

        // Specify project
        array_unshift($path, 'projects', $this->config->getProjectId());

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
        $responseCode = (int)$response->getCode();
        $message = 'TheApi call "'.$requestUrl.'" failed, status code: '.$responseCode.' '.$response->getCodeMessage();
        $message .= $this->getErrorResponseMessage((string)$response->getBody());

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
            return ' message: "'.$bodyData->message.'"';
        }

        return ' invalid error response format body: "'.$bodyString.'"';
    }
}
