# Payment method selection for a payment

Payment method selection can be fully customized on the application side.
It can be implemented as:
- [Preselection before payment creation](create-payment.md)
- [Payment method change after payment was created](change-payment-method-of-payment.md)

## Getting the payment methods

Whenever you want to display available payment methods to the customer, you must always request the current list from the API rather than relying on cached data.
The list of active methods can change at any time due to:
- Updates and additions in ThePay system
- Changes in your project’s configuration (set in ThePay administration)
- Temporary unavailability of certain methods

By fetching the list in real time, you ensure that the customer only sees valid and available payment options, preventing failed payments or a poor checkout experience.

```php
/** @var \ThePay\ApiClient\TheClient $thePayClient */

/**
 * Always obtain the current list of active payment methods
 * at the time you want to allow method selection.
 * The list can change at any time based on ThePay system
 * or your project settings.
 */
$activeMethods = $thePayClient->getActivePaymentMethods();

if ($activeMethods->size() === 0) {
    /**
     * We recommend checking if any method is active.
     * For example:
     * - Show an alert instead of the method selection form.
     * - Consider notifying the application maintainer
     *   that no payment method is currently available.
     */
}

/**
 * Display a payment method selection form
 * or send the method list to your frontend application.
 */
```
