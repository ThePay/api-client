# Get Information about payment

To get information about payment just simply call:

```php
$payment = $client->getPayment('49096fe3-872d-3cbe-b908-2806ae2d7c79');
```

The only parameter of method **getPayment** is a payment UID and the payment has to belong to the project which is configured in TheConfig.

You will get object describing the payment.
