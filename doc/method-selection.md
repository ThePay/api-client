# Payment method selection for payment

Payment method selection can be complete custom on an application side,
as [preselection before payment creation](create-payment.md) or as
[method change after payment was created](change-payment-method-of-payment.md) in ThePay system.

```php
/** @var \ThePay\ApiClient\TheClient $thePayClient */

/**
 * every time when you want select method, obtain an actual list of active methods,
 * because the list can change any time based on ThePay system or project settings!
 */
$activeMethods = $thePayClient->getActivePaymentMethods();

if($activeMethods->size() === 0) {
    /**
     * we recommend a check if any method is active,
     * for example, show some alert instead of method selection,
     * consider notifying application maintainer
     * that user can not select any method, ...
     */
}

/**
 * show some method selection form or send the method list to frontend app, ...
 */
```
