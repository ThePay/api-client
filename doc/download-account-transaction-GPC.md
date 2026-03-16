# Download account transaction GPC statement

Use the `getAccountStatementGPC` method to download the transaction GPC statement for a specific account within a given date range.

## Example: Download statement to local file on server

```php

/** @var \ThePay\ApiClient\TheClient $thePayClient */

$from = new DateTime('2021-03-01');
$to = new DateTime('2021-03-31');
$filter = new \ThePay\ApiClient\Filter\TransactionFilter('TP3211114680489551165349', $from, $to);

$psrResponseBodyStream = $thePayClient->getAccountStatementGPC($filter);
$phpResponseBodyStream = $psrResponseBodyStream->detach() ?? new \RuntimeException('PHP stream missing');

$file = fopen('some_path.gpc', 'w');
stream_copy_to_stream($phpResponseBodyStream, $file);
fclose($file);

```

**Parameters:**
- `$filter` - An instance of `\ThePay\ApiClient\Filter\TransactionFilter()`. (See online API documentation for all available filter options.)

**Returns:**

A `Psr\Http\Message\StreamInterface` object containing:
- Binary stream with GPC transaction statement in windows-1250 encoding
