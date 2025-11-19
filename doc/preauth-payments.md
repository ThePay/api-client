# Preauthorized payments

A **preauthorized payment** allows you to reserve (authorize) funds on a customer’s account and capture them later.
This is useful for cases like hotel bookings or rentals, where you charge the customer only after confirming service delivery.

By setting `setIsDeposit(false)` when creating a payment, the payment is created as a **preauthorization** instead of a direct deposit.
Typically, you have a limited period (usually 7 days) to realize the preauthorized payment.


## Create a Preauthorized Payment

To create a preauthorized payment, call `createPayment()` with the `isDeposit` flag set to `false`:

```php
/** @var \ThePay\ApiClient\TheClient $thePayClient */
/** @var \ThePay\ApiClient\Model\CreatePaymentCustomer $customer */
$params = new \ThePay\ApiClient\Model\CreatePaymentParams(100, 'CZK', 'PREAUTH_PAYMENT_001', $customer);
$params->setIsDeposit(false);

$thePayClient->createPayment($params);
```


## Realize a Preauthorized Payment

Once you are ready to capture the funds, call `realizePreauthorizedPayment()`:

```php
/** @var \ThePay\ApiClient\TheClient $thePayClient */
$params = new \ThePay\ApiClient\Model\RealizePreauthorizedPaymentParams(100, 'PREAUTH_PAYMENT_001');
$thePayClient->realizePreauthorizedPayment($params);
```

You may capture less than the originally preauthorized amount, but never more.

## Cancel a Preauthorized Payment

If you decide not to capture the funds, you can cancel the preauthorization:

```php
/** @var \ThePay\ApiClient\TheClient $thePayClient */
$thePayClient->cancelPreauthorizedPayment('PREAUTH_PAYMENT_001');
```

**Note on fund release:**
- While the preauthorization is cancelled immediately on ThePay’s side, banks may take some time to release the reserved funds back to the customer’s account.
