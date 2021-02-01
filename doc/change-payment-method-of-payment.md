# Change payment method of payment


You can change payment method of created payment that is waiting for payment. This can be useful when you disallow to customer to change payment method.


```php

/** @var $client \ThePay\ApiClient\TheClient */

// for example we pick first active payment method
$paymentMethod = $thePay->getActivePaymentMethods()[0];

$client->changePaymentMethod('UID_OF_PAYMENT', $paymentMethod);

```