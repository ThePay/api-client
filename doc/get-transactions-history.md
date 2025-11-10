# Get transaction history

Use the `getAccountTransactionHistory` method to retrieve the transaction history for a specific account within a given date range.

## Example: Retrieve Transactions

```php
/** @var \ThePay\ApiClient\TheClient $thePayClient */
$from = new DateTime('2021-03-01');
$to = new DateTime('2021-03-31');
$filter = new \ThePay\ApiClient\Filter\TransactionFilter('TP3211114680489551165349', $from, $to);
$transactionPaginatedCollection = $thePayClient->getAccountTransactionHistory($filter);
```

**Parameters:**
- `$filter` - An instance of `\ThePay\ApiClient\Filter\TransactionFilter()`. (See online API documentation for all available filter options.)
- `$page` *(optional)* — Page number.
- `$limit` *(optional)* — Number of records per page.

**Returns:**

A `ThePay\ApiClient\Model\Collection\TransactionCollection` object containing:
- A collection of transactions
- Current page number
- Number of records per page
- Includes helper methods such as `hasNextPage()` and `getPage()`

**Example: Iterate through all transactions**

```php
/** @var \ThePay\ApiClient\TheClient $thePayClient */
$from = new DateTime('2021-03-01');
$to = new DateTime('2021-03-31');
$filter = new \ThePay\ApiClient\Filter\TransactionFilter('TP3211114680489551165349', $from, $to);
$page = 1;

do {
    $collection = $thePayClient->getAccountTransactionHistory($filter, $page);

    foreach ($collection->all() as $transaction) {
        // print logic
    }

    $page = $collection->getPage() + 1;
} while($collection->hasNextPage());
```
