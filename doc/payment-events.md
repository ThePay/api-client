# Payment Events

Each payment contains an array of **events** that describe actions and state changes related to that payment.
Events can represent, for example:
- Method selection
- State change
- Unavailable payment method
- Payment cancellation
- Payment error

When a payment is first created, the events array is empty. It is populated gradually as the customer interacts with the payment process.

## Retrieve Payment Events

First, obtain the payment details:

```php
// Get payment detail
$payment = $thePayClient->getPayment('49096fe3-872d-3cbe-b908-2806ae2d7c79');
```

Then, retrieve the list of all events associated with the payment:

```php
$paymentEvents = $payment->getEvents();
```

`$paymentEvents` contains all recorded events for that payment.

## Check for Specific Events

You can also check whether certain events occurred during the user's **last attempt**.

### Example: Detect an Error on the Last Attempt

```php
$hasErrorOnLastAttempt = $payment->hasErrorOnLastAttempt();

if ($hasErrorOnLastAttempt) {
    // There was an error on the user's last attempt
} else {
    // No error occurred on the user's last attempt
}
```

## Example: Detect if User Cancelled the Last Attempt

```php
$wasLastAttemptCancelledByUser = $payment->wasLastAttemptCancelledByUser();

if ($wasLastAttemptCancelledByUser) {
    // The last attempt was cancelled by the user
} else {
    // The last attempt was not cancelled by the user
}
```
