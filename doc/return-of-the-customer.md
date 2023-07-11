# Return of the Customer

After payment creation, you will have to wait to return of the customer, keep in mind:

- the customer may not return to your website
- the payment may not be paid in the moment of the return

The customer will be returned to url specified in administration (in the project settings):

![settings](img/settings.png)

This value may be overridden for each payment in CreatePaymentParams by setReturnUrl() function.

There are two query parameters added to redirect url:

* payment_uid
* project_id

In most cases you want to check the state of payment after customer's return:

[See how to make TheClient](../.github/README.md#theclient-instance)

```php

$uid = $_GET["payment_uid"];
$projectId = $_GET["project_id"];

$merchantId = '86a3eed0-95a4-11ea-ac9f-371f3488e0fa';
$apiPassword = 'secret';
$apiUrl = 'https://demo.api.thepay.cz/'; // production: 'https://api.thepay.cz/'
$gateUrl = 'https://demo.gate.thepay.cz/'; // production: 'https://gate.thepay.cz/'

$config = new \ThePay\ApiClient\TheConfig($merchantId, $projectId, $apiPassword, $apiUrl, $gateUrl);

/** @var \ThePay\ApiClient\Service\ApiService $apiService */
$thePayClient = new \ThePay\ApiClient\TheClient($config, $apiService);

$payment = $thePayClient->getPayment($uid);
echo $payment->getState();
```
