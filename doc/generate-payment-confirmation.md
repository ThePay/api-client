# Generating a confirmation PDF for paid payment

If a merchant needs confirmation for someone that they received payment through our services.
They can generate a confirmation PDF document for payment in ThePay administration.

The generating confirmation can be also integrated into merchant application as describe example below.

```php

/** @var \ThePay\ApiClient\TheClient $thePayClient */

$payment = $thePayClient->getPayment('exampleUID');
// make detection if payment was really paid
// and inform user that for not paid payment is confirmation unavailable,
// because call method generatePaymentConfirmationPdf for unpaid payments will fail
if($payment->wasPaid()) {

    // we recommend asking user for language of receiver who confirmation wants, so he can understand it,
    // if we not support given language, we return confirmation in english
    $confirmationLanguageCode = 'cs';

    $confirmationPdfContent = $thePayClient->generatePaymentConfirmationPdf('exampleUID', $confirmationLanguageCode);

    header('Content-type:application/pdf');

    echo $confirmationPdfContent;
}

```
