# Change payment method of payment


After [method selection](method-selection.md), you can change payment method of created payment that is waiting for payment.
This can be useful when you disallow to customer to change payment method in ThePay system ( [Disable change of payment method](payment-disable-payment-method-change.md) ).


```php
/** @var \ThePay\ApiClient\TheClient $client */

/** @var non-empty-string $paymentMethodCode one method selected by user */

$client->changePaymentMethod('UID_OF_PAYMENT', $paymentMethodCode);

```
