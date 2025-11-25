# Return of the Customer

After you create a payment, you redirect the customer to the payment gateway using the URL provided.
Once the customer finishes (or abandons) the process at the gateway, they may be redirected back to your website.

Keep in mind:
- The customer may **not** return to your website
- The payment may **not** be paid at the moment they return

## Configuring the Return URL

- The customer will be returned to url specified in administration (in the project settings):

![settings](img/settings.png)

You can override this value on a per-payment basis in `CreatePaymentParams` using the `setReturnUrl()` method.

## Return URL Parameters

When the customer is redirected back, the following query parameters are appended:
- payment_uid
- project_id

You can use these identifiers to load the current state of the payment:

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
