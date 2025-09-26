# Managing payment methods
## Getting the payment methods

Whenever you need to display payment methods to the customer, always request the current list from the API.
Do not rely on cached data — the available methods may change at any time due to:
- Updates or additions in the ThePay system
- Changes in your project’s configuration (set in ThePay administration)
- Temporary unavailability of specific methods

Fetching the list in real time ensures that customers only see valid, available options — preventing failed payments or a poor checkout experience.

```php
/** @var \ThePay\ApiClient\TheClient $thePayClient */

// Always fetch the current list of active methods
$activeMethods = $thePayClient->getActivePaymentMethods();

if ($activeMethods->size() === 0) {
    /**
     * No payment methods available.
     * Recommended handling:
     * - Show an alert instead of the method selection form
     * - Notify the application maintainer
     */
}

/**
 * Use $activeMethods to build a selection form
 * or forward the list to your frontend application.
 */
```

## Payment method preselection

Payment method selection can be fully customized on the application side.
It can be implemented as:
- Preselection before payment creation
- Changing the payment method after the payment was created

### Preselection before payment creation
You can preselect the payment method when creating a payment by passing it as the second argument to the method:

```php
/** @var \ThePay\ApiClient\TheClient $thePayClient */
/** @var \ThePay\ApiClient\Model\CreatePaymentCustomer $customer */

$params = new \ThePay\ApiClient\Model\CreatePaymentParams(10000, 'CZK', 'uid123', $customer);

// For example, pick the first active payment method
$paymentMethod = $thePayClient->getActivePaymentMethods()[0];

$thePayClient->createPayment($params, $paymentMethod);
```

By using this approach you can fully customize how you want to display the payment methods in your e-shop.

See [Disabling payment method change](#disabling-payment-method-change) if you want to enforce the preselected option.

### Changing the payment method

For payments that are still pending, you can update the payment method after the payment has been created.

Use the `changePaymentMethod()` call with the payment’s unique ID and the code of the new method.
The method code must match one of the values returned by `getActivePaymentMethods()`.

```php
/** @var \ThePay\ApiClient\TheClient $thePayClient */
/** @var non-empty-string $paymentMethodCode */

$thePayClient->changePaymentMethod('uid123', $paymentMethodCode);
```

### Disabling payment method change

By default, customers can change the payment method in the ThePay gateway, even if you preselect one.
To prevent this, call `setCanCustomerChangeMethod(false)` when creating the payment.

⚠️ When disabling changes, you must also provide a specific payment method.

```php
/** @var \ThePay\ApiClient\TheClient $thePayClient */
/** @var \ThePay\ApiClient\Model\CreatePaymentCustomer $customer */

$params = new \ThePay\ApiClient\Model\CreatePaymentParams(10000, 'CZK', 'uid123', $customer);
$params->setCanCustomerChangeMethod(false);

// Select a payment method (for example, the first available one)
$paymentMethod = $thePayClient->getActivePaymentMethods()[0];

$thePayClient->createPayment($params, $paymentMethod);
```
