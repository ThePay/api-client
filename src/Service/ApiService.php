<?php

namespace ThePay\ApiClient\Service;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\StreamInterface;
use ThePay\ApiClient\Exception\ApiExceptionInterface;
use ThePay\ApiClient\Exception\BadRequestApiException;
use ThePay\ApiClient\Exception\ConflictApiException;
use ThePay\ApiClient\Exception\ForbiddenApiException;
use ThePay\ApiClient\Exception\NotFoundApiException;
use ThePay\ApiClient\Exception\ServiceUnavailableApiException;
use ThePay\ApiClient\Exception\TooManyRequestsApiException;
use ThePay\ApiClient\Exception\UnauthorizedApiException;
use ThePay\ApiClient\Exception\UnprocessableContentApiException;
use ThePay\ApiClient\Filter\PaymentsFilter;
use ThePay\ApiClient\Filter\TransactionFilter;
use ThePay\ApiClient\Model\AccountBalance;
use ThePay\ApiClient\Model\Collection\PaymentCollection;
use ThePay\ApiClient\Model\Collection\PaymentMethodCollection;
use ThePay\ApiClient\Model\Collection\TransactionCollection;
use ThePay\ApiClient\Model\CreatePaymentParams;
use ThePay\ApiClient\Model\CreatePaymentResponse;
use ThePay\ApiClient\Model\PaginatedCollectionParams;
use ThePay\ApiClient\Model\Payment;
use ThePay\ApiClient\Model\PaymentMethodWithPayUrl;
use ThePay\ApiClient\Model\PaymentRefund;
use ThePay\ApiClient\Model\PaymentRefundInfo;
use ThePay\ApiClient\Model\Project;
use ThePay\ApiClient\Model\RealizeIrregularSubscriptionPaymentParams;
use ThePay\ApiClient\Model\RealizePaymentBySavedAuthorizationParams;
use ThePay\ApiClient\Model\RealizePreauthorizedPaymentParams;
use ThePay\ApiClient\Model\RealizePreauthorizedPaymentResult;
use ThePay\ApiClient\Model\RealizeRegularSubscriptionPaymentParams;
use ThePay\ApiClient\Model\RealizeUsageBasedSubscriptionPaymentParams;
use ThePay\ApiClient\Model\RecurringPaymentResult;
use ThePay\ApiClient\TheClient;
use ThePay\ApiClient\TheConfig;
use ThePay\ApiClient\Utils\Json;
use ThePay\ApiClient\ValueObject\Amount;
use ThePay\ApiClient\ValueObject\GPCPaymentIdentifier;
use ThePay\ApiClient\ValueObject\Identifier;
use ThePay\ApiClient\ValueObject\LanguageCode;
use ThePay\ApiClient\ValueObject\StringValue;
use ThePay\ApiClient\ValueObject\SubscriptionType;

/**
 * Class ApiService is standard implementation for communication with ThePay Rest API
 *
 */
class ApiService implements ApiServiceInterface
{
    private const METHOD_GET = 'GET';
    private const METHOD_POST = 'POST';
    private const METHOD_DELETE = 'DELETE';
    private const METHOD_PUT = 'PUT';

    private const HEADER_SIGNATURE = 'Signature';
    private const HEADER_SIGNATURE_DATE = 'SignatureDate';
    private const HEADER_USER_AGENT = 'User-Agent';
    private const HEADER_PLATFORM = 'Platform';

    private TheConfig $config;
    private SignatureService $signatureService;
    private ClientInterface $httpClient;
    private RequestFactoryInterface $requestFactory;
    private StreamFactoryInterface $streamFactory;

    public function __construct(
        TheConfig $config,
        SignatureService $signatureService,
        ClientInterface $httpClient,
        RequestFactoryInterface $requestFactory,
        StreamFactoryInterface $streamFactory
    ) {
        $this->config = $config;
        $this->signatureService = $signatureService;
        $this->httpClient = $httpClient;
        $this->requestFactory = $requestFactory;
        $this->streamFactory = $streamFactory;
    }

    public function getProjects(): array
    {
        $url = $this->config->getApiUrl() . 'projects?merchant_id=' . $this->config->getMerchantId();
        $response = $this->sendRequest(self::METHOD_GET, $url);

        if ($response->getStatusCode() !== 200) {
            throw $this->buildException($url, $response);
        }

        $projects = [];
        foreach (Json::decode($response->getBody()->getContents(), true) as $item) {
            $projects[] = new Project(
                $item['project_id'],
                $item['project_url'],
                $item['account_iban']
            );
        }

        return $projects;
    }

    public function getActivePaymentMethods(?LanguageCode $languageCode = null): PaymentMethodCollection
    {
        $arguments = [];
        if ($languageCode) {
            $arguments['language'] = $languageCode->getValue();
        }

        $url = $this->url(['methods'], $arguments);
        $response = $this->sendRequest(self::METHOD_GET, $url);

        if ($response->getStatusCode() !== 200) {
            throw $this->buildException($url, $response);
        }

        return new PaymentMethodCollection($response->getBody()->getContents());
    }

    public function getAccountsBalances(?StringValue $accountIban = null, $projectId = null, ?\DateTime $balanceAt = null)
    {
        $arguments = [];
        if ($accountIban !== null) {
            $arguments['account_iban'] = $accountIban->getValue();
        }
        if ($projectId !== null) {
            $arguments['project_id'] = $projectId;
        }
        if ($balanceAt) {
            $arguments['balance_at'] = $balanceAt->format(\DateTime::ATOM);
        }

        $url = $this->url(['balances'], $arguments, false);
        $response = $this->sendRequest(self::METHOD_GET, $url);

        if ($response->getStatusCode() !== 200) {
            throw $this->buildException($url, $response);
        }

        $responseArray = Json::decode($response->getBody()->getContents(), true);

        return array_map(
            static function (array $accountBalance) {
                return new AccountBalance(
                    $accountBalance['iban'],
                    $accountBalance['name'],
                    $accountBalance['balance']
                );
            },
            $responseArray
        );
    }

    public function getAccountTransactionHistory(TransactionFilter $filter, int $page = 1, int $limit = 100): TransactionCollection
    {
        $paginatedCollectionParams = new PaginatedCollectionParams($filter, $page, $limit);

        $url = $this->url(['transactions', $filter->getAccountIban()], $paginatedCollectionParams->toArray(), false);
        $response = $this->sendRequest(self::METHOD_GET, $url);

        if ($response->getStatusCode() !== 200) {
            throw $this->buildException($url, $response);
        }

        return new TransactionCollection(Json::decode($response->getBody()->getContents(), true), $page, $limit, (int) $response->getHeaderLine('X-Total-Count'));
    }

    public function getAccountStatementGPC(TransactionFilter $filter, ?GPCPaymentIdentifier $paymentIdentifier = null): StreamInterface
    {
        $arguments = [
            'date_from' => $filter->getDateFrom()->format('Y-m-d'),
            'date_to' => $filter->getDateTo()->format('Y-m-d'),
        ];
        if ($filter->getCurrencyCode() !== null) {
            $arguments['currency_code'] = $filter->getCurrencyCode()->getValue();
        }
        if ($paymentIdentifier !== null) {
            $arguments['payment_identifier'] = $paymentIdentifier->getValue();
        }

        $url = $this->url(['transactions', $filter->getAccountIban(), 'account_statement', 'gpc'], $arguments, false);

        $response = $this->sendRequest(self::METHOD_GET, $url);
        if ($response->getStatusCode() !== 200) {
            throw $this->buildException($url, $response);
        }

        return $response->getBody();
    }

    public function getPayment(Identifier $paymentUid): Payment
    {
        $url = $this->url(['payments', $paymentUid]);
        $response = $this->sendRequest(self::METHOD_GET, $url);

        if ($response->getStatusCode() !== 200) {
            throw $this->buildException($url, $response);
        }

        return new Payment($response->getBody()->getContents());
    }

    public function invalidatePayment(Identifier $paymentUid): void
    {
        $url = $this->url(['payments', $paymentUid, 'invalidate']);
        $response = $this->sendRequest(self::METHOD_PUT, $url);

        if ($response->getStatusCode() !== 200) {
            throw $this->buildException($url, $response);
        }
    }

    public function getPayments(PaymentsFilter $filter, int $page = 1, int $limit = 25): PaymentCollection
    {
        $paginatedCollectionParams = new PaginatedCollectionParams($filter, $page, $limit);

        $url = $this->url(['payments'], $paginatedCollectionParams->toArray());
        $response = $this->sendRequest(self::METHOD_GET, $url);

        if ($response->getStatusCode() !== 200) {
            throw $this->buildException($url, $response);
        }

        return new PaymentCollection($response->getBody()->getContents(), $page, $limit, (int) $response->getHeaderLine('X-Total-Count'));
    }

    public function realizeRegularSubscriptionPayment(Identifier $parentPaymentUid, RealizeRegularSubscriptionPaymentParams $params): RecurringPaymentResult
    {
        $jsonParams = $params->toArray();

        $url = $this->url(['projects', $this->config->getProjectId(), 'payments', $parentPaymentUid, 'subscription', SubscriptionType::REGULAR], [], false, 'v2');
        $response = $this->sendRequest(self::METHOD_POST, $url, $jsonParams);

        if ( ! in_array($response->getStatusCode(), [200, 202], true)) {
            throw $this->buildException($url, $response);
        }

        return new RecurringPaymentResult($response->getBody()->getContents());
    }

    public function realizeIrregularSubscriptionPayment(Identifier $parentPaymentUid, RealizeIrregularSubscriptionPaymentParams $params): RecurringPaymentResult
    {
        $jsonParams = $params->toArray();

        $url = $this->url(['projects', $this->config->getProjectId(), 'payments', $parentPaymentUid, 'subscription', SubscriptionType::IRREGULAR], [], false, 'v2');
        $response = $this->sendRequest(self::METHOD_POST, $url, $jsonParams);

        if ( ! in_array($response->getStatusCode(), [200, 202], true)) {
            throw $this->buildException($url, $response);
        }

        return new RecurringPaymentResult($response->getBody()->getContents());
    }

    public function realizeUsageBasedSubscriptionPayment(Identifier $parentPaymentUid, RealizeUsageBasedSubscriptionPaymentParams $params): RecurringPaymentResult
    {
        $jsonParams = $params->toArray();

        $url = $this->url(['projects', $this->config->getProjectId(), 'payments', $parentPaymentUid, 'subscription', SubscriptionType::USAGE_BASED], [], false, 'v2');
        $response = $this->sendRequest(self::METHOD_POST, $url, $jsonParams);

        if ( ! in_array($response->getStatusCode(), [200, 202], true)) {
            throw $this->buildException($url, $response);
        }

        return new RecurringPaymentResult($response->getBody()->getContents());
    }

    public function realizePaymentBySavedAuthorization(Identifier $parentPaymentUid, RealizePaymentBySavedAuthorizationParams $params): RecurringPaymentResult
    {
        $jsonParams = $params->toArray();

        $url = $this->url(['projects', $this->config->getProjectId(), 'payments', $parentPaymentUid, 'savedauthorization'], [], false, 'v2');
        $response = $this->sendRequest(self::METHOD_POST, $url, $jsonParams);

        if ( ! in_array($response->getStatusCode(), [200, 202], true)) {
            throw $this->buildException($url, $response);
        }

        return new RecurringPaymentResult($response->getBody()->getContents());
    }

    public function createPayment(CreatePaymentParams $createPaymentParams, ?string $methodCode = null): CreatePaymentResponse
    {
        $jsonParams = $createPaymentParams->toArray();
        if ($methodCode !== null) {
            $jsonParams['payment_method_code'] = $methodCode;
        }

        $url = $this->url(['payments']);
        $response = $this->sendRequest(self::METHOD_POST, $url, $jsonParams);

        if ( ! in_array($response->getStatusCode(), [200, 201], true)) {
            throw $this->buildException($url, $response);
        }

        return new CreatePaymentResponse($response->getBody()->getContents(), $response->getStatusCode() === 201);
    }

    public function changePaymentMethod(Identifier $uid, string $methodCode): void
    {
        $url = $this->url(['payments', $uid, 'method']);
        $response = $this->sendRequest(
            self::METHOD_PUT,
            $url,
            ['payment_method_code' => $methodCode]
        );

        if ($response->getStatusCode() !== 204) {
            throw $this->buildException($url, $response);
        }
    }

    public function realizePreauthorizedPayment(RealizePreauthorizedPaymentParams $params): RealizePreauthorizedPaymentResult
    {
        $url = $this->url([
            'projects',
            $this->config->getProjectId(),
            'payments',
            $params->getUid(),
            'preauthorized',
        ], [], false, 'v2');
        $response = $this->sendRequest(self::METHOD_POST, $url, $params->toArray());

        if ( ! in_array($response->getStatusCode(), [200, 202], true)) {
            throw $this->buildException($url, $response);
        }

        return new RealizePreauthorizedPaymentResult($response->getBody()->getContents());
    }

    public function cancelPreauthorizedPayment(Identifier $uid): void
    {
        $url = $this->url(['payments', $uid, 'preauthorized']);
        $response = $this->sendRequest(self::METHOD_DELETE, $url);

        if ($response->getStatusCode() !== 204) {
            throw $this->buildException($url, $response);
        }
    }

    public function getPaymentRefund(Identifier $uid): PaymentRefundInfo
    {
        $url = $this->url(['payments', $uid->getValue(), 'refund']);
        $response = $this->sendRequest(self::METHOD_GET, $url);
        if ($response->getStatusCode() !== 200) {
            throw $this->buildException($url, $response);
        }

        $responseData = Json::decode($response->getBody()->getContents());

        $refunds = [];
        foreach ($responseData->partial_refunds as $responseRefund) {
            $refunds[] = new PaymentRefund($responseRefund->amount, $responseRefund->reason, $responseRefund->state);
        }

        return new PaymentRefundInfo($responseData->available_amount, $responseData->currency, $refunds);
    }

    public function createPaymentRefund(Identifier $uid, Amount $amount, string $reason): void
    {
        $url = $this->url(['payments', $uid->getValue(), 'refund']);
        $response = $this->sendRequest(
            self::METHOD_POST,
            $url,
            [
                'amount' => $amount->getValue(),
                'reason' => $reason,
            ]
        );

        if ($response->getStatusCode() !== 201) {
            throw $this->buildException($url, $response);
        }
    }

    public function getPaymentUrlsForPayment(Identifier $uid, ?LanguageCode $languageCode = null): array
    {
        $arguments = [];
        if ($languageCode) {
            $arguments['language'] = $languageCode->getValue();
        }

        $url = $this->url(['payments', $uid->getValue(), 'payment_urls'], $arguments);
        $response = $this->sendRequest(self::METHOD_GET, $url);

        if ($response->getStatusCode() !== 200) {
            throw $this->buildException($url, $response);
        }

        $responseData = Json::decode($response->getBody()->getContents(), true);

        $paymentMethods = [];
        foreach ($responseData as $paymentMethod) {
            $paymentMethods[] = new PaymentMethodWithPayUrl($paymentMethod);
        }

        return $paymentMethods;
    }

    public function generatePaymentConfirmationPdf(Identifier $uid, ?LanguageCode $languageCode = null): string
    {
        $arguments = [];
        if ($languageCode !== null) {
            $arguments['language'] = $languageCode->getValue();
        }

        $url = $this->url(['payments', $uid->getValue(), 'generate_confirmation'], $arguments);
        $response = $this->sendRequest(self::METHOD_GET, $url);

        if ($response->getStatusCode() !== 200) {
            throw $this->buildException($url, $response);
        }

        return $response->getBody()->getContents();
    }

    /**
     * Build URL for API requests
     *
     * @param array<string> $path
     * @param array<string, mixed> $arguments
     * @param bool $includeProject
     * @param non-empty-string|null $specificVersion
     * @return string
     */
    private function url($path = [], $arguments = [], $includeProject = true, ?string $specificVersion = null)
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

        $apiUrl = $this->config->getApiUrl($specificVersion);

        $pathImploded = substr($pathImploded, 0, -1);

        return $apiUrl . $pathImploded . '?' . http_build_query($arguments);
    }

    /**
     * @param self::METHOD_* $method
     * @param non-empty-string $uri
     * @param array<non-empty-string, mixed>|null $jsonBody
     */
    private function sendRequest(string $method, string $uri, ?array $jsonBody = null): ResponseInterface
    {
        $signature = $this->signatureService->getSignatureForApi();

        $request = $this->requestFactory->createRequest($method, $uri)

            ->withHeader(self::HEADER_SIGNATURE, $signature->getHash())
            ->withHeader(self::HEADER_SIGNATURE_DATE, $signature->getDate())

            ->withHeader(self::HEADER_USER_AGENT, 'ThePay Client/' . TheClient::VERSION . ' (PHP version ' . phpversion() . ')')
            ->withHeader(self::HEADER_PLATFORM, 'php_' . TheClient::VERSION)
        ;
        if ($jsonBody !== null) {
            $request = $request->withBody(
                $this->streamFactory->createStream(
                    Json::encode($jsonBody)
                )
            );
        }

        return $this->httpClient->sendRequest($request);
    }

    private function buildException(string $requestUrl, ResponseInterface $response): ApiExceptionInterface
    {
        $responseCode = $response->getStatusCode();
        $message = 'TheApi call "' . $requestUrl . '" failed, status code: ' . $responseCode . ' ' . $response->getReasonPhrase();
        $message .= $this->getErrorResponseMessage($response->getBody()->getContents());

        switch (true) {
            case $responseCode == 400:
                return new BadRequestApiException($message, $responseCode);
            case $responseCode == 401:
                return new UnauthorizedApiException($message, $responseCode);
            case $responseCode == 403:
                return new ForbiddenApiException($message, $responseCode);
            case $responseCode == 404:
                return new NotFoundApiException($message, $responseCode);
            case $responseCode == 409:
                return new ConflictApiException($message, $responseCode);
            case $responseCode == 422:
                return new UnprocessableContentApiException($message, $responseCode);
            case $responseCode == 429:
                return new TooManyRequestsApiException($message, $responseCode);
            case $responseCode >= 500:
            case $responseCode == 0: // network communication failure
                return new ServiceUnavailableApiException($message, $responseCode);
            default:
                return new class ($message, $responseCode) extends \RuntimeException implements ApiExceptionInterface {
                };
        }
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
