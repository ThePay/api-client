# Recommended payment creation

Example with optional detail information about customer.

```php
use ThePay\ApiClient\Model\Address;
use ThePay\ApiClient\Model\CreatePaymentCustomer;
use ThePay\ApiClient\TheConfig;
use ThePay\ApiClient\TheClient;
use ThePay\ApiClient\Model\CreatePaymentParams;

$merchantId = '86a3eed0-95a4-11ea-ac9f-371f3488e0fa';
$projectId = 3;
$apiPassword = 'secret';
// Connection to demo for testing
$apiUrl = 'https://demo.api.thepay.cz/';
$gateUrl = 'https://demo.gate.thepay.cz/';

$config = new TheConfig($merchantId, $projectId, $apiPassword, $apiUrl, $gateUrl);
$thePay = new TheClient($config);

// Create entity with information about customer
$customer = new CreatePaymentCustomer(
    'Mike',
    'Smith',
    'mike.smith@example.com',
    // Phone number in international format max 15 numeric chars https://en.wikipedia.org/wiki/MSISDN
    '420589687963',
    // Create billing address
    new Address('CZ', 'Prague', '123 00', 'Downstreet 5')
);

// Create payment (105.20 € with unique id uid123)
$createPayment = new CreatePaymentParams(10520, 'EUR', 'uid123');
$createPayment->setOrderId('15478');
$createPayment->setDescriptionForCustomer('Payment for items on example.com');
$createPayment->setDescriptionForMerchant('Payment from VIP customer XYZ');
$createPayment->setCustomer($customer);

$payment = $thePay->createPayment($createPayment);

// Get url where user can pay
echo $payment->getPayUrl(); // https://demo.gate.thepay.cz/5aa4f4af546a74848/pay/
```
