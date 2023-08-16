# Saving card authorization

It is possible to remember customer card information by using the save_authorization parameter upon payment creation.This allow easier and seamless payment creation, through following child payments, without additional customer card authorization.It shoud however NOT be confused with (and used for) [subscription payments](subscription.md).

## Creating payment with save_authorization flag

```php

/** @var \ThePay\ApiClient\TheClient $thePayClient */

$createPayment = new \ThePay\ApiClient\Model\CreatePaymentParams(10520, 'EUR', 'savedauthtest');

// set save_authorization to true
$createPayment->setSaveAuthorization(true);

$payment = $thePayClient->createPayment($createPayment);

// Get url where user can pay
echo $payment->getPayUrl(); // https://demo.gate.thepay.cz/5aa4f4af546a74848/pay/
```

## Realizing new payment by saved authorization

After the created payment was paid, we can realize new payment by saved authorization:

```php
/** @var \ThePay\ApiClient\TheClient $thePayClient */

// first parameter is uid of new (child) payment
// second parameter contains amount in cents, if sets to null it will use amount of parent payment, required if third parameter is set
// third parameter contains currency code, if sets to null it will use currency code of parent payment, required if second parameter is set
$params = new \ThePay\ApiClient\Model\RealizePaymentBySavedAuthorizationParams('childpayment', 1000, 'EUR');

// adding items is optional, if you do not add any item, items from parent payment will be used
$item = new \ThePay\ApiClient\Model\CreatePaymentItem('item', 'Server setup', 1000, 1);
$params->addItem($item);


// first parameter is uid of parent payment (the one we create above with savedAuthorization set to true).
// method will return ApiResponse
$response = $thePayClient->realizePaymentBySavedAuthorization('subscriptionpayment', $params);

if ($response->wasSuccessful()) {
    echo 'Payment was realized using saved authorization';
}
```
