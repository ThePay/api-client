# Preauthorized payments

In case you need to create preauthorized payment, just call createPayment() with params->setIsDeposit(false);
If deposit is set to false then the payment will be created as "pre-authorization" and you can realise it later using realizePreauthorizedPayment method. You usually have a period of 7 days to realise the pre-authorized payment.

```php
/** @var $client \ThePay\ApiClient\TheClient */
$params = new \ThePay\ApiClient\Model\CreatePaymentParams(100, 'CZK', 'PREAUTH_PAYMENT_001');
$params->setIsDeposit(false);
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
