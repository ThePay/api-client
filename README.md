#  PHP SDK for ThePay.cz

This is the official highly compatible public package of The Pay SDK which
interacts with The Pay's REST API. To get started see examples below.

## Requirements

- PHP 5.3+
- **curl** extension
- **json** extension

## Installation

To install the SDK we recommend to use [Composer](https://getcomposer.org/):

    composer require thepay/api-client

## Preconditions

Make sure that you have all required credentials and that you've set up the API access in [administration](https://admin.thepay.cz):

- merchant ID
- project ID
- password for API access
- enabled your IP address in project settings (you have to add IP address or IP address range of your server)

**To test the integration** you can create simplified "ready-to-go" DEMO account in our [DEMO environment](https://demo.admin.thepay.cz/registration).

You can find all the necessary credentials in "Implementation" section under your merchant profile:

![](doc/img/the-admin-credentials.png)

## Usage

```php
use ThePay\ApiClient\TheConfig;
use ThePay\ApiClient\TheClient;
use ThePay\ApiClient\Model\CreatePaymentParams;

$merchantId = '86a3eed0-95a4-11ea-ac9f-371f3488e0fa';
$projectId = 3;
$apiPassword = 'secret';
$apiUrl = 'https://demo.api.thepay.cz/'; // production: 'https://api.thepay.cz/'
$gateUrl = 'https://demo.gate.thepay.cz/'; // production: 'https://gate.thepay.cz/'

$config = new TheConfig($merchantId, $projectId, $apiPassword, $apiUrl, $gateUrl);
$thePay = new TheClient($config);

// Render payment methods for payment (100,- Kč)
$paymentParams = new CreatePaymentParams(10000, 'CZK', 'uid124');

echo $thePay->getPaymentButton($paymentParams);
```

## Configuration

```php
$config = new ThePay\ApiClient\TheConfig(
    $merchantId,
    $projectId,
    $apiPassword,
    $apiUrl,
    $gateUrl
);

$config->setLanguage($language);
```

| Argument | Type | Description |
| --- | --- | --- |
| `merchantId` | string | the identifier of merchant |
| `projectId` | int | the identifier of project, merchant may have a multiple projects |
| `apiPassword` | string | password for API, should not be the same as the password for logging into administration |
| `apiUrl` | string | base url for all API calls |
| `gateUrl` | string | gate application base url for user frontend |
| `language` | string | You may override this parameter later in request parameters, but this one will be used as a default value. The component requires format [ISO 639‑1](https://en.wikipedia.org/wiki/List_of_ISO_639-1_codes). Default value is **cs**. |

## More usage examples

You can find more usage examples at [folder /doc](doc/index.md).

## Money calculations

For safe money calculations we recommend to use [moneyphp/money](https://github.com/moneyphp/money) package.
Please, do not use float to save information about prices because of its inaccuracy.

    composer require moneyphp/money

## Support & Contributions

If you find any bug, please submit the issue in Github directly or contact us on email: [it@thepay.cz](mailto:it@thepay.cz)

Feel free to contribute via Github issues and pull requests. We will response as soon as possible.
Please have on mind the backwards compatibility and do not change requirements without previous admin agreement.
