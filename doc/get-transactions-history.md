# Get transaction history

To get transaction just simply call:

```php
/** @var \ThePay\ApiClient\TheClient $thePayClient */
$from = new DateTime('2021-03-01');
$to = new DateTime('2021-03-31');
$filter = new \ThePay\ApiClient\Filter\TransactionFilter('TP3211114680489551165349', $from, $to);
$transactionPaginatedCollection = $thePayClient->getAccountTransactionHistory($filter);
```

The first parameter of method **getAccountTransactionHistory** is filter object `\ThePay\ApiClient\Filter\TransactionFilter()`. All filter parameters are described in [Apiary](https://thepay.docs.apiary.io/#reference/0/merchant-level-resources/get-account-transaction-history).

Second and third parameter are used for pagination, where second is page number and third is number of records per page. Parameters are not required.

You will get object `ThePay\ApiClient\Model\Collection\TransactionCollection`, which contains collection of transactions, current page number, number of records per page and helper methods.

You can print all records by this call:

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
