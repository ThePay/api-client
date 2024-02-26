# Notifications

If state of the payment changes you will get a notification to a specified url. Please set up default value in administration:

![settings](img/settings.png)

This value may be overridden for each payment in CreatePaymentParams by setNotifUrl() function.

There are three query parameters added to notifications url:

* payment_uid
* project_id
* type (which determines notification type - see [Notification types enum](https://thepay.docs.apiary.io/#introduction/enums/notification-types))

In most cases you want to check the state of payment after getting notification:

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
if ($payment->getState() === 'paid') {
    // send email to customer
}
```

You can save server resources by filtering notification type to only certain types:

```php

$uid = $_GET["payment_uid"];
$projectId = $_GET["project_id"];
$type = $_GET["type"];

// handle only state changed notifications
if ($type === "state_changed") {
    $merchantId = '86a3eed0-95a4-11ea-ac9f-371f3488e0fa';
    $apiPassword = 'secret';
    $apiUrl = 'https://demo.api.thepay.cz/'; // production: 'https://api.thepay.cz/'
    $gateUrl = 'https://demo.gate.thepay.cz/'; // production: 'https://gate.thepay.cz/'

    $config = new \ThePay\ApiClient\TheConfig($merchantId, $projectId, $apiPassword, $apiUrl, $gateUrl);

    /** @var \ThePay\ApiClient\Service\ApiService $apiService */
    $thePayClient = new \ThePay\ApiClient\TheClient($config, $apiService);

    $payment = $thePayClient->getPayment($uid);
    if ($payment->getState() === 'paid') {
        // send email to customer
    }
}
```
