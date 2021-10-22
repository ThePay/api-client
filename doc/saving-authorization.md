# Saving card authorization

It is possible to remember customer card information by using the save_authorization parameter upon payment creation.This allow easier and seamless payment creation, through following child payments, without additional customer card authorization.It shoud however NOT be confused with (and used for) [subscription payments](subscription.md).

## Creating payment with save_authorization flag

```php
use ThePay\ApiClient\Model\Address;
use ThePay\ApiClient\Model\CreatePaymentCustomer;
use ThePay\ApiClient\TheConfig;
use ThePay\ApiClient\TheClient;
use ThePay\ApiClient\Model\CreatePaymentParams;
use ThePay\ApiClient\Model\RealizePaymentBySavedAuthorizationParams;
use ThePay\ApiClient\Model\CreatePaymentItem;

$merchantId = '86a3eed0-95a4-11ea-ac9f-371f3488e0fa';
$projectId = 3;
$apiPassword = 'secret';
// Connection to demo for testing
$apiUrl = 'https://demo.api.thepay.cz/';
$gateUrl = 'https://demo.gate.thepay.cz/';

$config = new TheConfig($merchantId, $projectId, $apiPassword, $apiUrl, $gateUrl);
$thePay = new TheClient($config);

$createPayment = new CreatePaymentParams(10520, 'EUR', 'savedauthtest');

// set save_authorization to true
$createPayment->setSaveAuthorization(true);

$payment = $thePay->createPayment($createPayment);

// Get url where user can pay
echo $payment->getPayUrl(); // https://demo.gate.thepay.cz/5aa4f4af546a74848/pay/
```

## Realizing new payment by saved authorization

After the created payment was paid, we can realize new payment by saved authorization:

```php
// first parameter is uid of new (child) payment
// second parameter contains amount in cents, if sets to null it will use amount of parent payment, required if third parameter is set
// third parameter contains currency code, if sets to null it will use currency code of parent payment, required if second parameter is set
$params = new RealizePaymentBySavedAuthorizationParams('childpayment', 1000, 'EUR');

// adding items is optional, if you do not add any item, items from parent payment will be used
$item = new CreatePaymentItem('item', 'Server setup', 1000, 1);
$params->addItem($item);


// first parameter is uid of parent payment (the one we create above with savedAuthorization set to true).
// method will return ApiResponse
$response = $thePay->realizePaymentBySavedAuthorization('subscriptionpayment', $params);

if ($response->wasSuccessful()) {
    echo 'Payment was realized using saved authorization';
}
```