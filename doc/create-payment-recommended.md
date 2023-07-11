# Recommended payment creation

Example with optional detail information about customer.

```php
/** @var \ThePay\ApiClient\TheClient $thePayClient */

// Create entity with information about customer
$customer = new \ThePay\ApiClient\Model\CreatePaymentCustomer(
    'Mike',
    'Smith',
    'mike.smith@example.com',
    // Phone number in international format max 15 numeric chars https://en.wikipedia.org/wiki/MSISDN
    '420589687963',
    // Create billing address
    new Address('CZ', 'Prague', '123 00', 'Downstreet 5')
);

// Create payment (105.20 € with unique id uid123)
$createPayment = new \ThePay\ApiClient\Model\CreatePaymentParams(10520, 'EUR', 'uid123');
$createPayment->setOrderId('15478');
$createPayment->setDescriptionForCustomer('Payment for items on example.com');
$createPayment->setDescriptionForMerchant('Payment from VIP customer XYZ');
$createPayment->setCustomer($customer);

$payment = $thePayClient->createPayment($createPayment);

// Get url where user can pay
echo $payment->getPayUrl(); // https://demo.gate.thepay.cz/5aa4f4af546a74848/pay/
```

### Changing payment's language

In scenarios where you know the customer's preferred language, you can pass the language code in `CreatePaymentParams` constructor as the fourth argument. For example:

```php
$createPayment = new \ThePay\ApiClient\Model\CreatePaymentParams(10520, 'EUR', 'uid123', 'en');
```

Possible values are described in ISO 639-1 standard. If you pass a language, that ThePay does not support, for example French (fr), then the English language will be used,
as is the most likely best choice for the customer. However, if the customer changed their language in ThePay system, then this setting will not have any impact.

You may wonder which language will be used if you do not enter any, then the language from TheConfig will be used, and if even here you did not select the default language,
the default language will be Czech (cs).
