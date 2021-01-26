# Disable payment method change for customer

You can disallow to customer to change payment method using setCanCustomerChangeMethod()

```php
/** @var $client \ThePay\ApiClient\TheClient */
$params = new \ThePay\ApiClient\Model\CreatePaymentParams(100, 'CZK', 'DISABLE_METHOD_CHANGE');
$params->setCanCustomerChangeMethod(false);
$client->createPayment($params);
```
