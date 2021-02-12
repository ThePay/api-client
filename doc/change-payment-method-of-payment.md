# Change payment method of payment


You can change payment method of created payment that is waiting for payment. This can be useful when you disallow to customer to change payment method ( [Disable change of payment method](payment-disable-payment-method-change.md) ).


```php

/** @var $client \ThePay\ApiClient\TheClient */

// for example we pick first active payment method
$paymentMethodCode = PaymentMethodCode::TRANSFER;

$client->changePaymentMethod('UID_OF_PAYMENT', $paymentMethodCode);

```