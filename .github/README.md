#  PHP SDK for ThePay.cz

This is the official highly compatible public package of The Pay SDK which
interacts with The Pay's REST API. To get started see examples below.

## Requirements

All necessary requirements are defined in [composer.json](../composer.json) `require` property.
We strongly recommend SDK installation using [Composer](https://getcomposer.org/)!

## Installation

```console
composer require thepay/api-client
```

Installation with suggested PSR http client.

```console
composer require thepay/api-client guzzlehttp/guzzle
```

This project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## Support & Contributions

If you find any bug, please submit the [issue](https://github.com/ThePay/api-client/issues/new/choose) to GitHub directly.

Feel free to contribute via Github [issues](https://github.com/ThePay/api-client/issues) and
[pull requests](https://github.com/ThePay/api-client/pulls). We will respond as soon as possible.
Please keep in mind backward compatibility, and do not change the requirements without prior administrator agreement.

## Preconditions
### Testing the integration
**To test the integration** you can create simplified "ready-to-go" DEMO account in our [DEMO environment](https://demo.admin.thepay.cz/registration).

You can find all the necessary credentials in "Implementation" section under your merchant profile:

![](../doc/img/the-admin-credentials.png)

### Access credentials
Make sure that you have all required credentials and that you've set up the API access in [administration](https://admin.thepay.cz), in the Implementation section. The required credentials are:
- merchant ID
- project ID
- password for API access

### IP address whitelisting
You must whitelist the IP address of the machine which will be accessing the API in the project settings.
You can use a particular IP address or specify a range. The whitelisting setup can be found in the same place as the credentials, that is the Implementation section of the administration.

## Usage

You will work with two classes when using this SDK.
- TheConfig - for setting up the library
- TheClient - for core functionality (calling the API, rendering helpers)

## Configuration with TheConfig

All constructor parameters are described in [php doc](../src/TheConfig.php)

```php
$merchantId = '86a3eed0-95a4-11ea-ac9f-371f3488e0fa';
$projectId = 3;
$apiPassword = 'secret';
$apiUrl = 'https://demo.api.thepay.cz/'; // production: 'https://api.thepay.cz/'
$gateUrl = 'https://demo.gate.thepay.cz/'; // production: 'https://gate.thepay.cz/'
$language = 'cs';

$theConfig = new \ThePay\ApiClient\TheConfig(
    $merchantId,
    $projectId,
    $apiPassword,
    $apiUrl,
    $gateUrl
);

$theConfig->setLanguage($language);
```

## TheClient instance

Make sure to prepare the necessary dependencies before creating the `\ThePay\ApiClient\TheClient` instance.

### With dependency injection
If you're using automatic dependency injection (as most frameworks do), all dependencies except `TheConfig`
(which you configured in the previous section) will be injected automatically - including PSR-standard interfaces,
provided your application already includes implementations of them.

### Without dependency injection
In case you are not using dependency injection, you will have to setup the classes manually yourself,
as in the following example:

```php
/** @var \ThePay\ApiClient\TheConfig $theConfig */

// TheClient instance dependencies
$signatureService = new \ThePay\ApiClient\Service\SignatureService($theConfig);
/** @var \Psr\Http\Client\ClientInterface $httpClient some PSR-18 implementation */
/** @var \Psr\Http\Message\RequestFactoryInterface $requestFactory some PSR-17 implementation */
/** @var \Psr\Http\Message\StreamFactoryInterface $streamFactory some PSR-17 implementation */
// if you install suggested guzzle implementation you can use this:
// $httpClient = new \GuzzleHttp\Client();
// $requestFactory = $streamFactory = new \GuzzleHttp\Psr7\HttpFactory();
$apiService = new \ThePay\ApiClient\Service\ApiService(
    $theConfig,
    $signatureService,
    $httpClient,
    $requestFactory,
    $streamFactory
);

$thePayClient = new \ThePay\ApiClient\TheClient(
    $theConfig,
    $apiService
);
```

## Usual payment workflow

There are three steps when creating a payment:
- creating a payment link through which the customer will realize the payment
- handling the return of customer to your website
- handling server to server notification, which are sent by us everytime the payment state is changed

All of these steps will need to be implemented by yourself, but fear not, we have prepared examples that you can take on your journey through our SDK.

### 1. Payment creation
#### REST API

The payment is created via the REST API, after which the customer is typically redirected to the URL provided in the response.

```php

/** @var \ThePay\ApiClient\TheClient $thePayClient */

// Specify the payment parameters (100,- Kč) including it's unique identifier
$paymentParams = new \ThePay\ApiClient\Model\CreatePaymentParams(10000, 'CZK', 'uid123');

// Get the payment link and redirect customer whenever you want
$payment = $thePayClient->createPayment($createPayment);
$redirectLink = $payment->getPayUrl();
```
For more details and examples see [create-payment.md](../doc/create-payment.md)

#### Payment method
You have two options, regarding whether the payment method is preselected or not:
- Payment method preselected in your e-shop (upon payment creation or later)
- Payment method NOT preselected - the customer will select payment method at ThePay gateway

The payment method can be preselected on your side simply by adding a parameter to the API call for payment creation.
By using this approach you can fully customize how you want to display the payment methods in your e-shop.
In addition, it is also possible to change the payment method from your side after it has been already created, if you need to do so for some reason.

If you do not preselect the payment method, the customer will be presented with payment method selection upon
visiting ThePay gateway through the generated link.

Note that even if the payment method is set from your side, the customer can still change it in ThePay gateway after redirection — unless this is explicitly forbidden.
To prevent changes, you must specify the setting when creating the payment by adding the appropriate parameter to the payment creation API call.

You can find more examples of the mentioned use cases here:
- [Preselection before payment creation](../doc/create-payment.md)
- [Payment method change after payment was created](../doc/change-payment-method-of-payment.md)
- [Disable change of payment method](../doc/payment-disable-payment-method-change.md)

#### Payment amount is unchangeable

Please note that the amount for which the payment was created cannot be changed later.

This means if the order is updated on your side and the final amount of the payment changes,
you will need to initiate a new payment via a new API call with the updated amount (and a new unique identifier).

Once a payment is created, it is not possible to modify the amount.

#### Payment flow and changes

You should always create only one payment (with its unique UID) for each order in your e-shop.
This means that if the customer navigates back and forth, they should use the same payment link to complete the process.

A new payment should be created only if the order itself changes (e.g., the final amount changes).

#### TL;DR - summary
- The payment is created via a call to our API.
- The payment method selection can be done either in your e-shop or through ThePay gateway.
- Always create only one payment per order, regardless of how the payment is initiated — unless the payment amount changes. In that case, treat it as an entirely new payment.

### 2. Customer return

After a successful payment — or if the customer decides to return to the e-shop without completing the payment — they are redirected to the return URL.

#### Return URL address

The return URL should point to the page in your e-shop where you want the customer to land after leaving the payment gateway.

You can set the return URL either in ThePay administration or by passing it as a parameter when creating the payment.
- If the return URL is set in ThePay administration, the parameter is optional and will override the configured value if provided.
- If the return URL is not set in ThePay administration, then the parameter is required when creating the payment.

#### Query parameters
When the customer is redirected, two query parameters are appended to the URL:
- payment_uid
- project_id

These parameters can be used, for example, to distinguish between different projects if you use the same return endpoint for multiple e-shops.

#### Payment state check upon return

The payment state must always be verified when the customer returns to your e-shop, as it may not yet be in the "paid" state.
For example, the customer might return without completing the payment.

#### General example of handling the customer return

[See how to make TheClient](#theclient-instance)

```php

/** @var \ThePay\ApiClient\TheClient $thePayClient */

$payment = $thePayClient->getPayment($uid);

// check if the payment is paid
if ($payment->wasPaid()) {
    // Check if the order isn't labeled as paid yet in your e-shop. If not, do so here.
    // ...
}
```

### 3. Server to server notification

A payment may take some time to process, or the customer may not return to your e-shop (e.g., by closing the browser window).
You don’t need to worry about this — whenever the payment state changes, we will automatically send a server-to-server notification to your system.

Notifications are triggered every time the payment state changes, for example, when the payment is completed or expires.
Because not all state changes indicate a successful payment, you must always verify the current payment state upon receiving a notification to determine what has actually occurred.

#### Notification URL

Similar to the return URL, the notification URL can be set either in ThePay administration or passed as a parameter when creating the payment

#### Payment state check upon receiving a notification

The payment state check you perform here is the same as the one you should do when the customer returns to your e-shop.

[See how to make TheClient](#theclient-instance)

```php

/** @var \ThePay\ApiClient\TheClient $thePayClient */

$payment = $thePayClient->getPayment($uid);

// check if the payment is paid
if ($payment->wasPaid()) {
    // Check if the order isn't labeled as paid yet. If not, do so here.
    // ...
}
```

## More and detailed usage examples

You can find more usage examples at [folder /doc](../doc/index.md).

## Money calculations

For safe and accurate money calculations, we recommend using the [moneyphp/money](https://github.com/moneyphp/money) package.
Please do not use floats to store or calculate prices, as they can lead to precision errors.

```console
composer require moneyphp/money
```
