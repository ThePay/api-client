# Disable payment method change for customer

You can prevent the customer and yourself from changing the payment method by using the setCanCustomerChangeMethod() method.

```php
/** @var \ThePay\ApiClient\TheClient $thePayClient */
$params = new \ThePay\ApiClient\Model\CreatePaymentParams(10000, 'CZK', 'uid123');
$params->setCanCustomerChangeMethod(false);

// For example, pick the first active payment method
$paymentMethod = $thePayClient->getActivePaymentMethods()[0];

// A payment method must be specified when using setCanCustomerChangeMethod()
$thePayClient->createPayment($params, $paymentMethod);
```
