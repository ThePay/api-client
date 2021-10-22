# Subscription payments

It is possible to create long term subscription for your customers. The setup for subsription is done through regular endpoint for payment creation, but with the subscription properties set. The payment created can then be repeatedly paid by the customer, through a different endpoint.

There are three different use cases with different endpoints for subscription payments, depending on payment time and payment amount needs.
* Fixed time interval & fixed payment amount
* Variable time interval & fixed payment amount
* Fixed time interval & variable payment amount

The time interval or amount with fixed value is always set in the first (parent) payment upon payment creation. It is your responsibility to adhere to the values set in the parent payment. If you need to change the fixed payment amount or time period, a new subscription payment with different parameters must be used.

**RECURRING PAYMENTS MUST BE ENABLED ON PROJECT**

## Creating payment with subscription properties

```php
use ThePay\ApiClient\Model\Address;
use ThePay\ApiClient\Model\CreatePaymentCustomer;
use ThePay\ApiClient\TheConfig;
use ThePay\ApiClient\TheClient;
use ThePay\ApiClient\Model\CreatePaymentParams;
use ThePay\ApiClient\Model\Subscription;
use ThePay\ApiClient\Model\RealizeRegularSubscriptionPaymentParams;
use ThePay\ApiClient\Model\RealizeIrregularSubscriptionPaymentParams;
use ThePay\ApiClient\Model\RealizeUsageBasedSubscriptionPaymentParams;
use ThePay\ApiClient\ValueObject\SubscriptionType;
use ThePay\ApiClient\Model\CreatePaymentItem;

$merchantId = '86a3eed0-95a4-11ea-ac9f-371f3488e0fa';
$projectId = 3;
$apiPassword = 'secret';
// Connection to demo for testing
$apiUrl = 'https://demo.api.thepay.cz/';
$gateUrl = 'https://demo.gate.thepay.cz/';

$config = new TheConfig($merchantId, $projectId, $apiPassword, $apiUrl, $gateUrl);
$thePay = new TheClient($config);

// prepare subscription properties (first parameter is type, second parameter is day period between payments)
$subscription = new Subscription(SubscriptionType::REGULAR, 30);

// Create payment (105.20 € with unique id 'subscriptionpayment')
$createPayment = new CreatePaymentParams(10520, 'EUR', 'subscriptionpayment');
$createPayment->setSubscription($subscription);

$payment = $thePay->createPayment($createPayment);

// Get url where user can pay
echo $payment->getPayUrl(); // https://demo.gate.thepay.cz/5aa4f4af546a74848/pay/
```

## Realizing subscription payment

After the created payment was paid, we can realize subscription:

### Realizing regular subscription payment type

```php
// parameter is uid of new (child) payment
$params = new RealizeRegularSubscriptionPaymentParams('childpayment');

// adding items is optional, if you do not add any item, items from parent payment will be used
$item = new CreatePaymentItem('item', 'Magazine #2', 10520, 1);
$params->addItem($item);


// first parameter is uid of parent payment (the one we create above with subscription property).
// method will return ApiResponse
$response = $thePay->realizeRegularSubscriptionPayment('subscriptionpayment', $params);

if ($response->wasSuccessful()) {
    echo 'Subscription payment was realized';
}
```

### Realizing irregular subscription payment type

```php
// parameter is uid of new (child) payment
$params = new RealizeIrregularSubscriptionPaymentParams('childpayment2');

// adding items is optional, if you do not add any item, items from parent payment will be used
$item = new CreatePaymentItem('item', 'New book', 10520, 1);
$params->addItem($item);


// first parameter is uid of parent payment (the one we create above with subscription property).
// method will return ApiResponse
$response = $thePay->realizeIrregularSubscriptionPayment('subscriptionpayment', $params);

if ($response->wasSuccessful()) {
    echo 'Subscription payment was realized';
}
```

### Realizing usage based subscription payment type

```php
// first parameter is uid of new (child) payment
// second parameter is amount in cents (in parent payment currency)
$params = new RealizeUsageBasedSubscriptionPaymentParams('childpayment3', 18000);

// adding items is optional, if you do not add any item, items from parent payment will be used
$item = new CreatePaymentItem('item', 'Server usage', 18000, 1);
$params->addItem($item);


// first parameter is uid of parent payment (the one we create above with subscription property).
// method will return ApiResponse
$response = $thePay->realizeUsageBasedSubscriptionPayment('subscriptionpayment', $params);

if ($response->wasSuccessful()) {
    echo 'Subscription payment was realized';
}
```