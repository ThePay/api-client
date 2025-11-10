# Invalidate payment

Use the `invalidatePayment` method to cancel an already created payment.

A payment can only be invalidated if it has not yet been completed.

## Example: Invalidate a Payment

```php
$thePayClient->invalidatePayment('49096fe3-872d-3cbe-b908-2806ae2d7c79');
```

**Parameters:**
- `$uid` - The unique identifier (UID) of the payment.

The payment must belong to the project configured in `TheConfig`

**Returns:**
- `void` if the request was successful.
- Throws an exception if the payment cannot be invalidated (for example, if it was already paid).

**Best Practice: Check Payment State Before Invalidating**

```php
$paymentUid = '49096fe3-872d-3cbe-b908-2806ae2d7c79';
$payment = $thePayClient->getPayment($paymentUid);

if ($payment->getState() === PaymentState::WAITING_FOR_PAYMENT) {
    $thePayClient->invalidatePayment($paymentUid);
} else {
    // payment can not be invalidated
}
```
