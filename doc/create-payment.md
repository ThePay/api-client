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
$paymentParams = new \ThePay\ApiClient\Model\CreatePaymentParams(10000, 'CZK', 'uid123');

/** @var \ThePay\ApiClient\Model\CreatePaymentResponse $payment */
$payment = $thePayClient->createPayment($paymentParams);

$redirectLink = $payment->getPayUrl();
// $redirectLink = $payment->getPaymentDetailUrl();
```

*CreatePaymentResponse* has two url properties for redirecting the customer to the payment gateway:
- getPayUrl() - The URL where the customer can directly proceed with the payment.
- getPaymentDetailUrl() - The URL showing the payment state and details, from which the customer can also complete the payment.

### Payment method

When creating a payment, you can either let the customer choose the payment method at ThePay gateway, or you can preselect a method in your application.
- **Without preselection** — no payment method is set during payment creation.
  The customer will be asked to choose a method directly in the ThePay gateway.
  *(This is the default behavior.)*
- **With preselection** — you define the payment method in your e-shop.
  This can be done immediately when creating the payment, or later by updating the payment before it is completed.

For details and examples of fetching available methods, preselecting a method, changing it, or preventing customers from changing it, see [Managing payment methods](../doc/managing-payment-methods).

### Payment language

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
