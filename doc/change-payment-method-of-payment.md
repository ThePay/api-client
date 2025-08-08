# Change payment method of payment

After [method selection](method-selection.md), you can change the payment method of a payment that is still awaiting completion.

```php
/** @var \ThePay\ApiClient\TheClient $thePayClient */

/** @var non-empty-string $paymentMethodCode one method selected by user */

$thePayClient->changePaymentMethod('uid123', $paymentMethodCode);
```
