# Generating a Payment Confirmation PDF

Merchants can generate a payment confirmation PDF as proof that a specific payment was successfully received through ThePay.

This document can be generated manually in ThePay Administration, or automatically via API integration in your application.

## API integration example

To integrate payment confirmation generation into your application, you can use the following example:

```php

/** @var \ThePay\ApiClient\TheClient $thePayClient */

$payment = $thePayClient->getPayment('uid123');

// Check if the payment has been successfully completed.
// Confirmation can only be generated for paid payments.
if ($payment->wasPaid()) {

    // Optionally, ask the user to select the preferred language for the confirmation document.
    // If the specified language is not supported, the PDF will default to English.
    $confirmationLanguageCode = 'cs';

    // Generate the confirmation PDF
    $confirmationPdfContent = $thePayClient->generatePaymentConfirmationPdf('uid123', $confirmationLanguageCode);

    header('Content-type:application/pdf');

    echo $confirmationPdfContent;
}

```
