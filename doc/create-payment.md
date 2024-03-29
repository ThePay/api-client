# Creating payment

You can create payment in two different ways:

1. the payment will be created by redirecting user to payment gate, the data will be sent by POST parameters
2. create payment using our API and then redirect user to payment gate, it's recommended way if you want to customize the way how to display payment methods

## Usage

| parameter | description |
| --- | --- |
| amount | Amount to pay in cents |
| currency | Currency code |
| uid | unique ID for payment |

The rest of parameters can be set via setters,
look at [CreatePaymentParams model](../src/Model/CreatePaymentParams.php).

Let's prepare payment of 1 CZK:

```php
   $params = new \ThePay\ApiClient\Model\CreatePaymentParams(100, 'CZK', '20200101000001');
```

### Redirect user to gate without payment method

```php
    $button = $thePayClient->getPaymentButton($params);
```

### Redirect user to gate with specific payment method and/or specify custom button attributes

After [method selection](method-selection.md), you can create payment with payment method
and method selection step is for user skipped in ThePay system.

```php
    /** @var non-empty-string $paymentMethodCode one method selected by user */

    $buttonAttributes = array('class' => 'btn btn-success');
    $button = $thePayClient->getPaymentButton($params, 'Button text', true, $paymentMethodCode, $buttonAttributes);
```

### Redirect user to gate with payment method selected

Method **getPaymentButtons** returns HTML code with form sended by click on payment link button.

```php
    /** @var string $paymentButtons */
    // used default rendering
    $paymentButtons = $thePayClient->getPaymentButtons($params);
    // Filter payment methods by tag. If tags are empty, all available methods will be displayed.
    $onlyMethodsWithTags = array(
        \ThePay\ApiClient\ValueObject\PaymentMethodTag::ONLINE,
    );
    $onlyMethodsWithoutTags = array(
        \ThePay\ApiClient\ValueObject\PaymentMethodTag::ALTERNATIVE_METHOD,
    );
    // used filtered methods
    // only payment methods matched used filter will be rendered in HTML,
    // but be still available for user in payment process!
    $filter = new \ThePay\ApiClient\Filter\PaymentMethodFilter(array(), $onlyMethodsWithTags, $onlyMethodsWithoutTags);
    $paymentButtons = $thePayClient->getPaymentButtons($params, $filter);
    // used without css styles
    // third bool parameter disable default css styles and javascript for payment method buttons
    // css styles are rendered in <style> tag in begin of rendered HTML
    // if default styles are disabled some custom styles must be implemented
    $paymentButtons = $thePayClient->getPaymentButtons($params, null, false);
```

Payment method buttons should look like this, second image is with hover.

![default](img/payment_method_button.png)
![hover](img/payment_method_button_hover.png)

#### Buttons css customization

Example of rendered HTML for one button, values with **some** word can dynamically change in HTML rendering.

```html
<style type="text/css">
    /* Some our css styles */
</style>
<script type="text/javascript">
    /* Some our JavaScript code */
</script>
<form action="..." method="post" id="some-unique-form-id">
    <!-- Some our hidden data to send with form -->
</form>
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

To render buttons without default inline styles and javascript,
set **$useInlineAssets** parameter to false and join CSS and JS on your own.

!!! Don't forget to add JavaScript file when you disable inline assets !!!

```php
    // The third parameter disables joining inline styles and javascript
    echo $thePayClient->getPaymentButtons($params, null, false);
```

Adding thepay javascript to your own package
```javascript
require('__thpay_api_location__/assets/dist/thepay')
```

To modify default styles, set **$useInlineAssets** parameter to **false**
and use our scss package which is located at **assets/scss/thepay.scss**
and customize our default variables located at **assets/scss/_variables.scss**

```scss
// Custom gray color
$tp-gray: #999999;
// Custom default border radius
$tp-base-radius:5px;
// Custom basic spacing e.g. between buttons
$tp-spacing: 0.75rem;
// Import thepay scss package to make the magic
@import "__thepay_api_location__/assets/scss/thepay";
```

If buttons are not rendered on screen as in image example or something on page is broken,
can be collision in page css and our default css.
If collision on your page cannot be easy solved,
we recommend disable our css and implement some css on your own.
If even our HTML is not suitable for you we recommend create payment via API.

### Via API

```php
    /** @var \ThePay\ApiClient\Model\CreatePaymentResponse $response */
    $response = $thePayClient->createPayment($params);
```

*CreatePaymentResponse* has two properties with url for redirection user to payment gate:

- getPayUrl() for url where user can make payment
- getPaymentDetailUrl() for url where are details about payment and user can make payment as well

### Changing payment's language

In scenarios where you know the customer's preferred language, you can pass the language code in `CreatePaymentParams` constructor as the fourth argument. For example:

```php
$createPayment = new CreatePaymentParams(10520, 'EUR', 'uid123', 'en');
```

Possible values are described in ISO 639-1 standard. If you pass a language, that ThePay does not support, for example French (fr), then the English language will be used,
as is the most likely best choice for the customer. However, if the customer changed their language in ThePay system, then this setting will not have any impact.

You may wonder which language will be used if you do not enter any, then the language from TheConfig will be used, and if even here you did not select the default language,
the default language will be Czech (cs).
