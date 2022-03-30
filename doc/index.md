# Available methods and usage

[![Gate documentation](https://img.shields.io/static/v1?label=Gate&message=documentation&logo=data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAHgAAAB4CAYAAAA5ZDbSAAAEs2lUWHRYTUw6Y29tLmFkb2JlLnhtcAAAAAAAPD94cGFja2V0IGJlZ2luPSLvu78iIGlkPSJXNU0wTXBDZWhpSHpyZVN6TlRjemtjOWQiPz4KPHg6eG1wbWV0YSB4bWxuczp4PSJhZG9iZTpuczptZXRhLyIgeDp4bXB0az0iWE1QIENvcmUgNS41LjAiPgogPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4KICA8cmRmOkRlc2NyaXB0aW9uIHJkZjphYm91dD0iIgogICAgeG1sbnM6ZXhpZj0iaHR0cDovL25zLmFkb2JlLmNvbS9leGlmLzEuMC8iCiAgICB4bWxuczp0aWZmPSJodHRwOi8vbnMuYWRvYmUuY29tL3RpZmYvMS4wLyIKICAgIHhtbG5zOnBob3Rvc2hvcD0iaHR0cDovL25zLmFkb2JlLmNvbS9waG90b3Nob3AvMS4wLyIKICAgIHhtbG5zOnhtcD0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wLyIKICAgIHhtbG5zOnhtcE1NPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvbW0vIgogICAgeG1sbnM6c3RFdnQ9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZUV2ZW50IyIKICAgZXhpZjpQaXhlbFhEaW1lbnNpb249IjEyMCIKICAgZXhpZjpQaXhlbFlEaW1lbnNpb249IjEyMCIKICAgZXhpZjpDb2xvclNwYWNlPSIxIgogICB0aWZmOkltYWdlV2lkdGg9IjEyMCIKICAgdGlmZjpJbWFnZUxlbmd0aD0iMTIwIgogICB0aWZmOlJlc29sdXRpb25Vbml0PSIyIgogICB0aWZmOlhSZXNvbHV0aW9uPSI3Mi4wIgogICB0aWZmOllSZXNvbHV0aW9uPSI3Mi4wIgogICBwaG90b3Nob3A6Q29sb3JNb2RlPSIzIgogICBwaG90b3Nob3A6SUNDUHJvZmlsZT0ic1JHQiBJRUM2MTk2Ni0yLjEiCiAgIHhtcDpNb2RpZnlEYXRlPSIyMDIw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)](https://gatezalozeniplatby.docs.apiary.io/#)
[![API documentation](https://img.shields.io/static/v1?label=API&message=documentation&logo=data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAHgAAAB4CAYAAAA5ZDbSAAAEs2lUWHRYTUw6Y29tLmFkb2JlLnhtcAAAAAAAPD94cGFja2V0IGJlZ2luPSLvu78iIGlkPSJXNU0wTXBDZWhpSHpyZVN6TlRjemtjOWQiPz4KPHg6eG1wbWV0YSB4bWxuczp4PSJhZG9iZTpuczptZXRhLyIgeDp4bXB0az0iWE1QIENvcmUgNS41LjAiPgogPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4KICA8cmRmOkRlc2NyaXB0aW9uIHJkZjphYm91dD0iIgogICAgeG1sbnM6ZXhpZj0iaHR0cDovL25zLmFkb2JlLmNvbS9leGlmLzEuMC8iCiAgICB4bWxuczp0aWZmPSJodHRwOi8vbnMuYWRvYmUuY29tL3RpZmYvMS4wLyIKICAgIHhtbG5zOnBob3Rvc2hvcD0iaHR0cDovL25zLmFkb2JlLmNvbS9waG90b3Nob3AvMS4wLyIKICAgIHhtbG5zOnhtcD0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wLyIKICAgIHhtbG5zOnhtcE1NPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvbW0vIgogICAgeG1sbnM6c3RFdnQ9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZUV2ZW50IyIKICAgZXhpZjpQaXhlbFhEaW1lbnNpb249IjEyMCIKICAgZXhpZjpQaXhlbFlEaW1lbnNpb249IjEyMCIKICAgZXhpZjpDb2xvclNwYWNlPSIxIgogICB0aWZmOkltYWdlV2lkdGg9IjEyMCIKICAgdGlmZjpJbWFnZUxlbmd0aD0iMTIwIgogICB0aWZmOlJlc29sdXRpb25Vbml0PSIyIgogICB0aWZmOlhSZXNvbHV0aW9uPSI3Mi4wIgogICB0aWZmOllSZXNvbHV0aW9uPSI3Mi4wIgogICBwaG90b3Nob3A6Q29sb3JNb2RlPSIzIgogICBwaG90b3Nob3A6SUNDUHJvZmlsZT0ic1JHQiBJRUM2MTk2Ni0yLjEiCiAgIHhtcDpNb2RpZnlEYXRlPSIyMDIw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)](https://dataapi21.docs.apiary.io/#)


| SDK method | API/GATE call | Description |
| --- | --- | --- |
| getProjects | https://dataapi21.docs.apiary.io/#reference/0/merchant-level-resources/get-projects | |
| getActivePaymentMethods | https://dataapi21.docs.apiary.io/#reference/0/project-level-resources/get-payment-methods |
| getPayment | https://dataapi21.docs.apiary.io/#reference/0/project-level-resources/get-payment-detail |
| getPayments | https://dataapi21.docs.apiary.io/#reference/0/project-level-resources/get-payments |
| getPaymentButtons | |
| getPaymentButton | |
| createPayment | https://dataapi21.docs.apiary.io/#reference/0/project-level-resources/create-new-payment |
| realizePreauthorizedPayment | https://dataapi21.docs.apiary.io/#reference/0/project-level-resources/realize-preauthorized-payment |
| cancelPreauthorizedPayment | https://dataapi21.docs.apiary.io/#reference/0/project-level-resources/cancel-preauthorized-payment |
| getPaymentRefund | https://dataapi21.docs.apiary.io/#reference/0/project-level-resources/payment-refund-info |
| createPaymentRefund | https://dataapi21.docs.apiary.io/#reference/0/project-level-resources/payment-refund-request |
| getAccountTransactionHistory | https://dataapi21.docs.apiary.io/#reference/0/merchant-level-resources/get-account-transaction-history |
| realizeRegularSubscriptionPayment | https://dataapi21.docs.apiary.io/#reference/0/project-level-resources/realize-regular-subscription-payment |
| realizeIrregularSubscriptionPayment | https://dataapi21.docs.apiary.io/#reference/0/project-level-resources/realize-irregular-subscription-payment |
| realizeUsageBasedSubscriptionPayment | https://dataapi21.docs.apiary.io/#reference/0/project-level-resources/realize-usage-based-subscription-payment |
| realizePaymentBySavedAuthorization | https://dataapi21.docs.apiary.io/#reference/0/project-level-resources/realize-payment-by-saved-authorization |

## Usage examples

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
