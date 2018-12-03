# Recurring Payments

Firstly, you will have to create payment with parameter for recurring payments.

```php
/** @var $client \ThePay\ApiClient\TheClient */
$params = new \ThePay\ApiClient\Model\CreatePaymentParams(100, 'CZK', 'RECURRING_001');
$params->setIsRecurring(true);
$client->createPayment($params);
```

To realize recurring payment (to get actual money), you have to call method:

```php
/** @var $client \ThePay\ApiClient\TheClient */
$params = new \ThePay\ApiClient\Model\CreateRecurringPaymentParams('RECURRING_001', 'SUBREC_PAYMENT_001', 1, 100, 'CZK');
$client->realizeRecurringPayment($params);
```
