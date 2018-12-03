

/**
 * On data-thepay="payment-button" click handler.
 * Send form if has attribute data-form-id.
 */
ThePayApi.registerActionEvent('payment-button', 'click', function (ev) {
    if (ThePayApi.helpers.elementHasData(this, 'form-id')) {
        let formEl = document.getElementById(ThePayApi.helpers.elementGetData(this, 'form-id'));
        if (formEl) {
            ev.preventDefault();
            let paymentMethod = ThePayApi.helpers.elementGetData(this, 'payment-method');
            ThePayApi.sendPaymentRequestForm(formEl, paymentMethod);
            return false;
        }
    }
    return true;
});

/**
 * Appends payment method and sends form with payment data
 * @param formEl
 * @param paymentMethod
 */
ThePayApi.sendPaymentRequestForm = function (formEl, paymentMethod) {
    if (paymentMethod) {
        let paymentMethodId = formEl.getAttribute('id') + '-method';
        let methodEl = document.getElementById(paymentMethodId);
        if ( ! methodEl) {
            methodEl = document.createElement('input');
            methodEl.setAttribute('type', 'hidden');
            methodEl.setAttribute('id', paymentMethodId);
            methodEl.setAttribute('name', 'payment_method_code');
            formEl.appendChild(methodEl);
        }
        methodEl.setAttribute('value', paymentMethod);
    }
    formEl.submit();
};
