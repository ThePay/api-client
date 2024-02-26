# Get pay URLs for existing payments

Returns an array of available payment methods with pay URLs for certain payment.

```php
/** @var \ThePay\ApiClient\TheClient $thePayClient */
$paymentMethod = $thePayClient->getPaymentUrlsForPayment('uid-454548', 'cs');
```

### Preformatted buttons

Method **getPaymentButtonsForPayment** returns HTML code.

```php
    // used default rendering
    $paymentButtons = $thePayClient->getPaymentButtonsForPayment($paymentUid);
```

Payment method buttons should look like this, second image is with hover.

![default](img/payment_method_button.png)
![hover](img/payment_method_button_hover.png)
