# Available methods and usage

[![API documentation](https://img.shields.io/static/v1?label=API&message=documentation)](https://thepay.docs.apiary.io/#)


| SDK method | API/GATE call | Description |
| --- | --- | --- |
| getProjects | https://thepay.docs.apiary.io/#reference/0/merchant-level-resources/get-projects | |
| getActivePaymentMethods | https://thepay.docs.apiary.io/#reference/0/project-level-resources/get-payment-methods |
| getPayment | https://thepay.docs.apiary.io/#reference/0/project-level-resources/get-payment-detail |
| getPayments | https://thepay.docs.apiary.io/#reference/0/project-level-resources/get-payments |
| getPaymentButtons | |
| getPaymentButton | |
| getPaymentButtonsForPayment | |
| createPayment | https://thepay.docs.apiary.io/#reference/0/project-level-resources/create-new-payment |
| realizePreauthorizedPayment | https://thepay.docs.apiary.io/#reference/0/project-level-resources/realize-preauthorized-payment |
| cancelPreauthorizedPayment | https://thepay.docs.apiary.io/#reference/0/project-level-resources/cancel-preauthorized-payment |
| getPaymentRefund | https://thepay.docs.apiary.io/#reference/0/project-level-resources/payment-refund-info |
| createPaymentRefund | https://thepay.docs.apiary.io/#reference/0/project-level-resources/payment-refund-request |
| getAccountTransactionHistory | https://thepay.docs.apiary.io/#reference/0/merchant-level-resources/get-account-transaction-history |
| realizeRegularSubscriptionPayment | https://thepay.docs.apiary.io/#reference/0/project-level-resources/realize-regular-subscription-payment |
| realizeIrregularSubscriptionPayment | https://thepay.docs.apiary.io/#reference/0/project-level-resources/realize-irregular-subscription-payment |
| realizeUsageBasedSubscriptionPayment | https://thepay.docs.apiary.io/#reference/0/project-level-resources/realize-usage-based-subscription-payment |
| realizePaymentBySavedAuthorization | https://thepay.docs.apiary.io/#reference/0/project-level-resources/realize-payment-by-saved-authorization |
| getPaymentUrlsForPayment | https://thepay.docs.apiary.io/#reference/payment-management/general-payment-management/get-available-payment-methods |

## Usage examples

[Payment method selection](method-selection.md)

[Creating payment](create-payment.md)

[Preauth payment](preauth-payments.md)

[Recommended payment creation](create-payment-recommended.md)

[Get Information about payment](get-payment.md)

[Get payments](get-payments.md)

[Payment events](payment-events.md)

[Refund payment](refund-payment.md)

[Disable change of payment method](payment-disable-payment-method-change.md)

[Change payment method of payment](change-payment-method-of-payment.md)

[Handling returns of customers](return-of-the-customer.md)

[Handling notification about changes](notifications.md)

[Get account transaction history](get-transactions-history.md)

[Creating subscription](subscription.md)

[Saving authorization](saving-authorization.md)

[Get pay URLs for existing payments](get-pay-urls-for-existing-payment.md)

[Generating a confirmation PDF for paid payment](generate-payment-confirmation.md)

## Methods

### getProjects

Return array of project instances created by merchant in thepay system.

| return type | |
| --- | --- |
| [Project](../src/Model/Project.php)[] | not null |

### getActivePaymentMethods

Returns list of available payment methods.

#### Parameters

| name | type |  |
| --- | --- | --- |
| $filter | PaymentMethodFilter | optional |
| $languageCode | LanguageCode | optional |

### getPayment

Returns all the information about specific payment.

#### Parameters

| name | type |  |
| --- | --- | --- |
| $paymentUid | string | required |

### getPayments

Returns list of all payments.

#### Parameters

| name | type |  |
| --- | --- | --- |
| $filter | PaymentFilter | optional |
| $page | int | optional |
| $limit | int | optional |

### getPaymentButtons

Returns HTML markup with list of payment buttons.

#### Parameters

| name | type |  | desc |
| --- | --- | --- | --- |
| $params | CreatePaymentParams | required | |
| $filter | PaymentMethodFilter | optional | |
| $useInlineAssets | bool | optional | will generate basic css & js |

### getPaymentButton

Returns HTML markup with "Pay!" button.

### getPaymentButtonsForPayment

Returns HTML markup with list of payment buttons for already existing payment.

#### Parameters

| name | type |  | desc |
| --- | --- | --- | --- |
| $uid | string | required | Payment's UID |
| $languageCode | string | optional | Language code in ISO 6391 (2 chars) format |
| $useInlineAssets | bool | optional | will generate basic css & js |

#### Parameters

| name | type |  | desc |
| --- | --- | --- | --- |
| $params | CreatePaymentParams | optional | |
| $title | string | optional | The title of the button, default is 'Pay!' |
| $useInlineAssets | bool | optional | will generate basic css & js |

### createPayment

Will create payment.

#### Parameters

| name | type |  |
| --- | --- | --- |
| $params | CreatePaymentParams | required |

### realizePreauthorizedPayment

Will realize preauth payment

#### Parameters

| name | type |  |
| --- | --- | --- |
| $params | RealizePreauthorizedPaymentParams | required |

### cancelPreauthorizedPayment

Will cancel preauth payment

#### Parameters

| name | type   |          | desc                                                |
|------|--------|----------|-----------------------------------------------------|
| $uid | string | required | UID of the preauthorized payment you want to cancel |


### getPaymentRefund

Returns information about payment refund

#### Parameters

| name | type | |
| --- | --- | --- |
| $uid | string | required |

| return type | |
| --- | --- |
| PaymentRefundInfo | not null |

### createPaymentRefund

Will create request for automatic refund of payment

#### Parameters

| name | type | |
| --- | --- | --- |
| $uid | string | required |
| $amount | int | required |
| $reason | string | required |

### getAccountTransactionHistory

Return information about transactions history

#### Parameters

| name | type |  |
| --- | --- | --- |
| $filter | TransactionFilter | required |
| $page | int | optional |
| $limit | int | optional |

### realizeRegularSubscriptionPayment

Realize subscription payment.

#### Parameters

| name | type |  | description |
| --- | --- | --- | --- |
| $uid | string | required | UID of parent payment |
| $params | RealizeRegularSubscriptionPaymentParams | required | |

### realizeIrregularSubscriptionPayment

Realize subscription payment.

#### Parameters

| name | type |  | description |
| --- | --- | --- | --- |
| $uid | string | required | UID of parent payment |
| $params | RealizeIrregularSubscriptionPaymentParams | required | |

### realizeUsageBasedSubscriptionPayment

Realize subscription payment.

#### Parameters

| name | type |  | description |
| --- | --- | --- | --- |
| $uid | string | required | UID of parent payment |
| $params | RealizeUsageBasedSubscriptionPaymentParams | required | |


### realizePaymentBySavedAuthorization

Create new payment using saved authorization.

#### Parameters

| name | type |  | description |
| --- | --- | --- | --- |
| $uid | string | required | UID of parent payment |
| $params | RealizePaymentBySavedAuthorizationParams | required | |

### getPaymentUrlsForPayment

Returns an array of available payment methods with pay URLs for certain payment.

#### Parameters

| name | type |  | description |
| --- | --- | --- | --- |
| $uid | string | required | Payment's UID |
| $languageCode | string | optional | Language code in ISO 6391 (2 chars) format |
