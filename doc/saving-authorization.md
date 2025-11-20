# Saving Card Authorization

It is possible to store a customer’s card authorization by enabling the `save_authorization` parameter when creating a payment.
This allows you to create follow-up (“child”) payments seamlessly, without requiring the customer to reauthorize their card.

However, this feature must not be confused with — or used for — [subscription payments](subscription.md). Those have a separate workflow and rules.

## Creating a Payment with `save_authorization`

```php

/** @var \ThePay\ApiClient\TheClient $thePayClient */

$createPayment = new \ThePay\ApiClient\Model\CreatePaymentParams(10520, 'EUR', 'uid_savedauthtest', $customer);

// Enable saving of card authorization
$createPayment->setSaveAuthorization(true);

$payment = $thePayClient->createPayment($createPayment);

// Redirect customer to this URL to complete the payment
echo $payment->getPayUrl(); // https://demo.gate.thepay.cz/5aa4f4af546a74848/pay/
```

Once the payment is successfully completed, the system will store a reusable authorization token associated with the payment.

## Creating a New Payment Using Saved Authorization

After the original payment (with `save_authorization = true`) has been paid, you can realize a new payment using the stored authorization:

```php
/** @var \ThePay\ApiClient\TheClient $thePayClient */

// First parameter: UID of the new (child) payment
// Second parameter: amount in cents (optional; if null, parent amount is used; required if currency is set)
// Third parameter: currency code (optional; if null, parent currency is used; required if amount is set)
$params = new \ThePay\ApiClient\Model\RealizePaymentBySavedAuthorizationParams('uid_childpayment', 1000, 'EUR');

// adding items is optional, if you do not add any item, items from parent payment will be used
$item = new \ThePay\ApiClient\Model\CreatePaymentItem('item', 'Server setup', 1000, 1);
$params->addItem($item);

// First parameter: UID of the parent payment (one created with saveAuthorization=true)
// The method returns ApiResponse
$response = $thePayClient->realizePaymentBySavedAuthorization('uid_savedauthtest', $params);

if ($response->wasSuccessful()) {
    echo 'Payment was realized using saved authorization';
}
```
