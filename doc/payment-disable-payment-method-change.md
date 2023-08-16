# Disable payment method change for customer

You can disallow to customer to change payment method using setCanCustomerChangeMethod()

```php
/** @var \ThePay\ApiClient\TheClient $thePayClient */
$params = new \ThePay\ApiClient\Model\CreatePaymentParams(100, 'CZK', 'DISABLE_METHOD_CHANGE');
$params->setCanCustomerChangeMethod(false);

// for example we pick first active payment method
$paymentMethod = $thePayClient->getActivePaymentMethods()[0];

// we must specify payment method if we used setCanCustomerChangeMethod()
$thePayClient->createPayment($params, $paymentMethod);
```
