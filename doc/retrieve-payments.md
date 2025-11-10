# Retrieving payments

Use these methods to retrieve payment details from ThePay API — either a single payment by UID or multiple payments using filters and pagination.

## Get a Single Payment

To retrieve information about a single payment, call:

```php
$payment = $thePayClient->getPayment('49096fe3-872d-3cbe-b908-2806ae2d7c79');
```

**Parameters:**
- string `$uid` — Unique identifier (UID) of the payment.

The payment must belong to the project configured in TheConfig.

**Returns:**

An object describing the payment.

## Get Multiple Payments

To retrieve multiple payments, you can use filters and pagination:

```php
/** @var \ThePay\ApiClient\TheClient $thePayClient */
$filters = new \ThePay\ApiClient\Filter\PaymentsFilter();
$paymentPaginatedCollection = $thePayClient->getPayments($filters);
```

**Parameters:**
- `$filters` — An instance of `\ThePay\ApiClient\Filter\PaymentsFilter()`. (See online API documentation for all available filter options.)
- `$page` *(optional)* — Page number.
- `$limit` *(optional)* — Number of records per page.

**Returns:**

A `PaymentCollection` object containing:
- A collection of payments
- Current page number
- Number of records per page
- Includes helper methods such as `hasNextPage()` and `getPage()`

**Example: Iterate through all pages**

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
