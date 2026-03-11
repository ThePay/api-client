# Download account transaction GPC statement

Use the `getAccountStatementGPC` method to download the transaction GPC statement for a specific account within a given date range.

## Example: Download statement to local file on server

```php
/** @var \ThePay\ApiClient\TheConfig $theConfig */

$signatureService = new \ThePay\ApiClient\Service\SignatureService($theConfig);

/**
 * PSR-18 client MUST be configured correctly, to return real PSR-7 network stream!
 * HTTP client CAN NOT read getAccountStatementGPC response body to memory at once!
 * Because GPC statement is not paginated, and can contain big amount of data!
 *
 * @var \Psr\Http\Client\ClientInterface $httpClient
 */
// if you use suggested guzzle implementation you MUST use RequestOptions with true value!
// https://docs.guzzlephp.org/en/stable/request-options.html#stream
//$httpClient = new \GuzzleHttp\Client([\GuzzleHttp\RequestOptions::STREAM => true]);
/** @var \Psr\Http\Message\RequestFactoryInterface $requestFactory */
/** @var \Psr\Http\Message\StreamFactoryInterface $streamFactory */

$apiService = new \ThePay\ApiClient\Service\ApiService(
    $theConfig,
    $signatureService,
    $httpClient,
    $requestFactory,
    $streamFactory
);

$thePayClient = new \ThePay\ApiClient\TheClient(
    $theConfig,
    $apiService,
);

$from = new DateTime('2021-03-01');
$to = new DateTime('2021-03-31');
$filter = new \ThePay\ApiClient\Filter\TransactionFilter('TP3211114680489551165349', $from, $to);

$psrResponseBodyStream = $thePayClient->getAccountStatementGPC($filter);
$phpResponseBodyStream = $psrResponseBodyStream->detach() ?? new \RuntimeException('PHP stream missing');

$file = fopen('some_path.gpc', 'w');
stream_copy_to_stream($phpResponseBodyStream, $file);
fclose($file);

```
