# Payment Events

Each payment detail has an array of events. In the array are events representing the following types: method selection, state change, unavailable method, payment cancelled, and payment error.
After creating a payment, the array will be empty and will be fulfilled depending on user actions.

First thing you need to do is to obtain payment detail:

```php
// get payment detail
$payment = $client->getPayment('49096fe3-872d-3cbe-b908-2806ae2d7c79');
```

Then you can get the list of events that occured on that payment:

```php
$paymentEvents = $payment->getEvents();
```

You can also check if there was some specific event on the user's last attempt.

To check if there was an error on the last attempt:

```php
$hasErrorOnLastAttempt = $payment->hasErrorOnLastAttempt();
if ($hasErrorOnLastAttempt) {
    // there was an error on the last attempt
} else {
    // there was not an error on the last attempt
}
```

To check if the last attempt was cancelled by user:

```php
$wasLastAttemptCancelledByUser = $payment->wasLastAttemptCancelledByUser();
if ($wasLastAttemptCancelledByUser) {
    // last attempt was cancelled by user
} else {
    // last attempt was not cancelled by user
}
```