# Preauthorized payments

In case you need to create preauthorized payment, just call createPayment() with params->setIsDeposit(true);

```php
/** @var $client \ThePay\ApiClient\TheClient */
$params = new \ThePay\ApiClient\Model\CreatePaymentParams(100, 'CZK', 'PREAUTH_PAYMENT_001');
$params->setIsDeposit(true);
$client->createPayment($params);
```

To realize payment call:

```php
/** @var $client \ThePay\ApiClient\TheClient */
$params = new \ThePay\ApiClient\Model\RealizePreauthorizedPaymentParams(100, 'PREAUTH_PAYMENT_001');
$client->realizePreauthorizedPayment($params);
```

If you want to cancel preauth:

```php
/** @var $client \ThePay\ApiClient\TheClient */
$client->cancelPreauthorizedPayment('PREAUTH_PAYMENT_001');
```
