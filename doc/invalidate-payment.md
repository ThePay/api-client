# Invalidate payment

To cancel (invalidate) already created payment.

```php
$client->invalidatePayment('49096fe3-872d-3cbe-b908-2806ae2d7c79');
```

The only parameter of method **invalidatePayment** is a payment UID and the payment has to belong to the project which is configured in TheConfig.

Method will return void if request was successful, otherwise it throws exception (For example if you are trying to invalidate payment that was already paid). Before invalidating payment is better to check its state:


```php
$payment = $client->getPayment('49096fe3-872d-3cbe-b908-2806ae2d7c79');
if ($payment->getState() === PaymentState::WAITING_FOR_PAYMENT) {
    $client->invalidatePayment('49096fe3-872d-3cbe-b908-2806ae2d7c79');
} else {
    // payment can not be invalidated
}
```