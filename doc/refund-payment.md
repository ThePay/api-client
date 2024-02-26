# Refund payment

Most paid payments can be automatically refund to customer, call createPaymentRefund($uid, $amount, $reason).

Detect if payment is refundable by call getPaymentRefund($uid)->getAvailableAmount() !== 0.

A refunded amount can be smaller than availableAmount for payment but cannot be bigger,
for one payment can be created multiple refunds.

Basic load information about payment refund,

after client call can be rendered information about refund how you like,
or refund form can be rendered if some amount for refund is available.

```php
/** @var \ThePay\ApiClient\TheClient $thePayClient */
$paymentRefundInfo = $thePayClient->getPaymentRefund('uid-454548');
```

Recommended refund action processing,

always check if refunded amount is available to avoid 403 api exception
and if not available render some message for user

```php
/** @var \ThePay\ApiClient\TheClient $thePayClient */
if($thePayClient->getPaymentRefund('uid-454548')->getAvailableAmount() >= 10000) {
    $thePayClient->createPaymentRefund('uid-454548', 10000, 'Partial refund because some items was delivered broken');
}
```
