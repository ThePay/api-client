# Get Payments

To get payments just simply call:

```php
/** @var \ThePay\ApiClient\TheClient $thePayClient */
$filters = new \ThePay\ApiClient\Filter\PaymentsFilter();
$paymentPaginatedCollection = $thePayClient->getPayments($filters);
```

The first parameter of method **getPayments** is filter object `\ThePay\ApiClient\Filter\PaymentsFilter()`. All filter parameters are described in Apiary.

Second and third parameter is used for pagination, where first is page number and third is number of records per page. Parameters are not required.

You will get object `ThePay\ApiClient\Model\Collection\PaymentCollection`, which contains collection of payments, current page number, number of records per page and helper methods.

You can print all records by this call:

```php
/** @var \ThePay\ApiClient\TheClient $thePayClient */
$filters = new \ThePay\ApiClient\Filter\PaymentsFilter();
$page = 1;
do {
    $collection = $thePayClient->getPayments($filters, $page);

    foreach ($collection->all() as $payment) {
        // print logic
    }

    $page = $collection->getPage() + 1;
} while($collection->hasNextPage());
```
