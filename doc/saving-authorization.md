# Saving Card Authorization

It is possible to store a customer's card authorization by enabling the `save_authorization` parameter when creating a payment.
This allows you to create follow-up ("child") payments seamlessly, without requiring the customer to reauthorize their card.

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
use ThePay\ApiClient\Model\RecurringPaymentResult;

/** @var \ThePay\ApiClient\TheClient $thePayClient */

$params = new \ThePay\ApiClient\Model\RealizePaymentBySavedAuthorizationParams(
    'uid_childpayment', // UID of the new (child) payment
    1000,               // Amount in cents
    'EUR'               // Currency code
);

// Items are optional; if none are added, the items from the parent payment are reused
$item = new \ThePay\ApiClient\Model\CreatePaymentItem('item', 'Server setup', 1000, 1);
$params->addItem($item);

// First parameter: UID of the parent payment (created with saveAuthorization=true)
// The method returns RecurringPaymentResult
$result = $thePayClient->realizePaymentBySavedAuthorization('uid_savedauthtest', $params);

match ($result->getState()) {
    RecurringPaymentResult::STATE_PAID =>
        echo 'Payment was realized using saved authorization',

    RecurringPaymentResult::STATE_WAITING_FOR_CONFIRMATION =>
        echo 'Payment is being processed; you will receive a notification when it completes',

    RecurringPaymentResult::STATE_ERROR =>
        echo 'Payment could not be realized',
};

// Determine whether the saved authorization can still be used for future payments
if (!$result->isRecurringPaymentsAvailable()) {
    // Saved authorization is no longer valid, customer needs to authorize a new payment
    echo 'Saved authorization is no longer valid; the customer must authorize a new payment';
}
```

The API supports asynchronous payment processing, with the following states:
- `paid` - Payment realized successfully
- `error` - Payment realization failed
- `waiting_for_confirmation` - Payment is being processed asynchronously
- You will receive a notification when async payments complete (state changes to `paid` or `error`)

**Saved authorization availability:** Check `isRecurringPaymentsAvailable()` to check if the saved authorization can still be used for future payments.
