# Subscription Payments

Subscription payments allow you to charge a customer repeatedly over time.
A subscription is initialized through a standard payment creation request, but with subscription properties attached.
Once the initial (parent) payment is completed, follow-up (child) payments can be realized using dedicated subscription endpoints.

There are three main subscription types, depending on whether the time interval and payment amount are fixed or variable:
- Fixed time interval & fixed amount
- Variable time interval & fixed amount
- Fixed time interval & variable amount

Any value marked as fixed must be set in the parent payment, and it is your responsibility to adhere to it when creating child payments.
If your subscription requires a different amount or interval, you must create a new parent subscription payment.

⚠️ **Important / Prerequisites**
- **Recurring payments must be enabled on your project**.


## Creating a Payment with Subscription Properties

```php
/** @var \ThePay\ApiClient\TheClient $thePayClient */

// Prepare subscription properties:
// First parameter: subscription type
// Second parameter: number of days between payments (for fixed interval types)
$subscription = new \ThePay\ApiClient\Model\Subscription(
    \ThePay\ApiClient\ValueObject\SubscriptionType::REGULAR,
    30
);

// Create initial subscription payment (105.20 € with UID 'uid_subscriptionpayment')
$createPayment = new \ThePay\ApiClient\Model\CreatePaymentParams(10520, 'EUR', 'uid_subscriptionpayment', $customer);
$createPayment->setSubscription($subscription);

$payment = $thePayClient->createPayment($createPayment);

// Redirect the customer to complete the payment
echo $payment->getPayUrl(); // https://demo.gate.thepay.cz/5aa4f4af546a74848/pay/
```

## Realizing subscription payment

After the parent payment is paid, you may charge the customer again using one of the three subscription realization types.

### Realizing a Regular (Fixed Interval & Fixed Amount) Subscription

```php
use ThePay\ApiClient\Model\RecurringPaymentResult;

/** @var \ThePay\ApiClient\TheClient $thePayClient */

// UID of the new (child) payment
$params = new \ThePay\ApiClient\Model\RealizeRegularSubscriptionPaymentParams('uid_childpayment');

// You may optionally define items. If omitted, items from parent payment are reused.
$item = new \ThePay\ApiClient\Model\CreatePaymentItem('item', 'Magazine #2', 10520, 1);
$params->addItem($item);

// Parent payment UID is passed as the first parameter.
// Method returns a RecurringPaymentResult.
$result = $thePayClient->realizeRegularSubscriptionPayment('uid_subscriptionpayment', $params);

match ($result->getState()) {
    RecurringPaymentResult::STATE_PAID =>
        echo 'Subscription payment was realized',

    RecurringPaymentResult::STATE_WAITING_FOR_CONFIRMATION =>
        echo 'Payment is being processed, you will receive a notification when complete',

    RecurringPaymentResult::STATE_ERROR =>
        echo 'Payment was not realized',
};

// Check if more payments can be realized with this parent
if (!$result->isRecurringPaymentsAvailable()) {
    // No more payments can be realized with this parent, inform customer to create new subscription
    echo 'Saved authorization is no longer valid, customer needs to authorize a new payment';
}
```

**Note about V2 API:**
The V2 API supports asynchronous payment processing:
- State `paid` means immediate success
- State `error` means immediate failure
- State `waiting_for_confirmation` means the payment is being processed asynchronously
- You will receive a notification when the async payment completes (state changes to `paid` or `error`)
- Always check `isRecurringPaymentsAvailable()` to know if the subscription should end

### Realizing an Irregular (Variable Interval & Fixed Amount) Subscription

```php
use ThePay\ApiClient\Model\RecurringPaymentResult;

/** @var \ThePay\ApiClient\TheClient $thePayClient */

// UID of the new (child) payment
$params = new \ThePay\ApiClient\Model\RealizeIrregularSubscriptionPaymentParams('uid_childpayment2');

// You may optionally define items. If omitted, items from parent payment are reused.
$item = new \ThePay\ApiClient\Model\CreatePaymentItem('item', 'New book', 10520, 1);
$params->addItem($item);

// Parent payment UID is passed as the first parameter.
// Method returns a RecurringPaymentResult.
$result = $thePayClient->realizeIrregularSubscriptionPayment('uid_subscriptionpayment', $params);

match ($result->getState()) {
    RecurringPaymentResult::STATE_PAID =>
        echo 'Subscription payment was realized',

    RecurringPaymentResult::STATE_WAITING_FOR_CONFIRMATION =>
        echo 'Payment is being processed, you will receive a notification when complete',

    RecurringPaymentResult::STATE_ERROR =>
        echo 'Payment was not realized',
};

// Check if more payments can be realized
if (!$result->isRecurringPaymentsAvailable()) {
    // Parent payment no longer available for new subscriptions
    echo 'Parent payment no longer available for new subscriptions';
}
```

### Realizing a Usage-Based (Fixed Interval & Variable Amount) Subscription

```php
use ThePay\ApiClient\Model\RecurringPaymentResult;

/** @var \ThePay\ApiClient\TheClient $thePayClient */

// First param: UID of child payment
// Second param: amount in cents (in the parent payment currency)
$params = new \ThePay\ApiClient\Model\RealizeUsageBasedSubscriptionPaymentParams('uid_childpayment3', 18000);

// You may optionally define items. If omitted, items from parent payment are reused.
$item = new \ThePay\ApiClient\Model\CreatePaymentItem('item', 'Server usage', 18000, 1);
$params->addItem($item);


// Parent payment UID is passed as the first parameter.
// Method returns a RecurringPaymentResult.
$result = $thePayClient->realizeUsageBasedSubscriptionPayment('uid_subscriptionpayment', $params);

match ($result->getState()) {
    RecurringPaymentResult::STATE_PAID =>
        echo 'Subscription payment was realized',

    RecurringPaymentResult::STATE_WAITING_FOR_CONFIRMATION =>
        echo 'Payment is being processed, you will receive a notification when complete',

    RecurringPaymentResult::STATE_ERROR =>
        echo 'Payment was not realized',
};

// Check if more payments can be realized
if (!$result->isRecurringPaymentsAvailable()) {
    // Parent payment no longer available for new subscriptions
    echo 'Parent payment no longer available for new subscriptions';
}
```
