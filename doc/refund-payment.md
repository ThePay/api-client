# Refund Payment

Most paid payments can be automatically refunded back to the customer.
To create a refund, call `createPaymentRefund($uid, $amount, $reason)`.

You can check whether a payment is refundable by inspecting the available refundable amount:

```php
$available = $thePayClient->getPaymentRefund($uid)->getAvailableAmount();
$isRefundable = ($available !== 0);
```

A refund can be **partial** or **full**:
- You may refund less than the available amount
- You may not refund more than the available amount
- You may create multiple refunds for one payment, as long as sufficient refundable amount remains

## Loading Refund Information

You can load the refund information for a payment and render it however you prefer—either as details for the user or to display a refund form when a refund is possible.

```php
/** @var \ThePay\ApiClient\TheClient $thePayClient */
$paymentRefundInfo = $thePayClient->getPaymentRefund('uid-454548');
```

## Recommended Refund Processing Flow

Before creating a refund, always verify that the requested refund amount is still available.
This prevents a `403` API exception and allows you to show a meaningful message to the user.

```php
/** @var \ThePay\ApiClient\TheClient $thePayClient */
$refundInfo = $thePayClient->getPaymentRefund('uid-454548');

if ($refundInfo->getAvailableAmount() >= 10000) {
    $thePayClient->createPaymentRefund(
        'uid-454548',
        10000,
        'Partial refund because some items were delivered broken'
    );
} else {
    // Render message: refund amount is not available
}
```
