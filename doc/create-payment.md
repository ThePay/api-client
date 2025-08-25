# Creating payment

The payment is created via the REST API, after which the customer is typically redirected to the URL provided in the response.

## Usage

| parameter | description            |
| --- |------------------------|
| amount | Amount to pay in cents |
| currency | Currency code          |
| uid | Unique ID of payment    |

The rest of parameters can be set via setters,
look at [CreatePaymentParams model](../src/Model/CreatePaymentParams.php).

As an example let's prepare a payment of 100 CZK:

```php
$params = new \ThePay\ApiClient\Model\CreatePaymentParams(10000, 'CZK', 'uid123');

/** @var \ThePay\ApiClient\Model\CreatePaymentResponse $response */
$response = $thePayClient->createPayment($params);

$redirectLink = $payment->getPayUrl();
// $redirectLink = $payment->getPaymentDetailUrl();
```

*CreatePaymentResponse* has two url properties for redirecting the customer to the payment gateway:
- getPayUrl() - The URL where the customer can directly proceed with the payment.
- getPaymentDetailUrl() - The URL showing the payment state and details, from which the customer can also complete the payment.

### Payment method

You have two options, regarding whether the payment method is preselected or not:
- Payment *WITH* preselected method in your e-shop (upon payment creation or later)
- Payment *WITHOUT* preselected method - the customer will choose the payment method at ThePay gateway

#### Preselecting the payment method

You can preselect the payment method when creating a payment by passing it as the second argument to the method:

```php
/** @var \ThePay\ApiClient\TheClient $thePayClient */
$params = new \ThePay\ApiClient\Model\CreatePaymentParams(10000, 'CZK', 'uid123');

// For example, pick the first active payment method
$paymentMethod = $thePayClient->getActivePaymentMethods()[0];

$thePayClient->createPayment($params, $paymentMethod);
```

By using this approach you can fully customize how you want to display the payment methods in your e-shop.
You can change the payment method on your side even after it has been created, if needed.

Note that even if the payment method is set from your side, the customer can still change it in ThePay gateway after redirection — unless this is explicitly forbidden.
To prevent changes, you must specify the setting when creating the payment by adding the appropriate parameter to the payment creation API call.

You can find more examples of the mentioned use cases here:
- [Payment method change after payment was created](../doc/change-payment-method-of-payment.md)
- [Disable change of payment method](../doc/payment-disable-payment-method-change.md)

#### Not preselecting the payment method

If you do not preselect the payment method, the customer will be prompted to choose one upon
visiting ThePay gateway via the generated link.

### Changing payment language

If you know the customer’s preferred language, you can specify it when creating a payment by passing the language code as the fourth argument of the `CreatePaymentParams` constructor:

```php
$params = new CreatePaymentParams(10520, 'EUR', 'uid123', 'en');
```

Language codes follow the ISO 639-1 standard.
- If you provide a language not supported by ThePay, the interface will default to English.
- If the customer has already chosen a language in ThePay, their personal setting will take precedence.
- If no language is provided, ThePay will use the language defined in your TheConfig. If that is also not set, the default will be Czech (cs).

### Obtaining Payment Buttons

You can either render your own payment method buttons (recommended for full customization), or use the built-in method **getPaymentButtonsForPayment**, which returns ready-to-use HTML:

```php
$paymentButtons = $thePayClient->getPaymentButtonsForPayment($paymentUid);
```

The default buttons look like this (the second image shows the hover state):

![default](img/payment_method_button.png)
![hover](img/payment_method_button_hover.png)

#### Buttons CSS customization

Example of rendered HTML for one button (values marked with **some** can change dynamically):

```html
<style type="text/css">
    /* Some default ThePay CSS styles */
</style>
<script type="text/javascript">
    /* Some default ThePay JavaScript code */
</script>
<div class="tp-btn-grid">
    <a href="..." class="tp-btn" thepay-data-attributes >
        <span class="tp-icon">
            <img src="..." alt="Payment method icon" >
        </span>
        <span class="tp-title" role="note">
            Převod z účtu
        </span>
    </a>
</div>
```

By default, the generated buttons include inline styles and JavaScript.
If you want full control over the styling and script loading, set the **$useInlineAssets** parameter to *false* when retrieving the buttons:

```php
// The second parameter disables joining inline styles and JavaScript
echo $thePayClient->getPaymentButtonsForPayment($params, false);
```

⚠️ Important: If you disable inline assets, you must include ThePay’s JavaScript manually:

```javascript
require('__thpay_api_location__/assets/dist/thepay')
```

#### Customizing styles with SCSS

To override default styles, disable inline assets and use our SCSS package:
- SCSS source: assets/scss/thepay.scss
- Variables for customization: assets/scss/_variables.scss

```scss
// Custom gray color
$tp-gray: #999999;

// Custom default border radius
$tp-base-radius: 5px;

// Custom basic spacing e.g. between buttons
$tp-spacing: 0.75rem;

// Import ThePay SCSS package
@import "__thepay_api_location__/assets/scss/thepay";
```

#### Handling CSS conflicts

If the buttons don’t render correctly (e.g., layout breaks or styles differ from the preview images), it may be caused by conflicts between your CSS and ThePay’s default styles.
In such cases, we recommend disabling ThePay’s CSS entirely and implementing your own styling.
