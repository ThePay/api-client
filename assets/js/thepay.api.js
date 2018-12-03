const ThePayApi = {};

window.ThePayApi = ThePayApi;

ThePayApi.init = function () {
    ThePayApi.initHandlers();
};

let helpers = ThePayApi.helpers = require('./thepay.api.helpers');

ThePayApi.initHandlers = function () {
    document.addEventListener('click', function (ev) {
        // Handle event if target or its parent contains thepay data attribute
        let target = ev.target;

        if (target && helpers.elementHasData(target, 'thepay')) {
            return ThePayApi.handleEvent.call(target, ev, helpers.elementGetData(target, 'thepay'));
        }
        // Try to find parent element with thepay data attribute
        let parent = helpers.elementParentByData(target, 'thepay');
        if (parent) {
            return ThePayApi.handleEvent.call(parent, ev, helpers.elementGetData(parent, 'thepay'));
        }
    });
};

// Event handler for elements with data-thepay attribute
ThePayApi.handlers = {};

/**
 *
 * @param Event ev
 * @param String action
 * @returns {*}
 */
ThePayApi.handleEvent = function (ev, action) {
    let evName = ev.type;
    if ((typeof ThePayApi.handlers[action] !== 'undefined') && (typeof ThePayApi.handlers[action][evName] !== 'undefined')) {
        return ThePayApi.handlers[action][evName].call(this, ev);
    }
};

ThePayApi.registerActionEvent = function(action, eventName, callback) {
    if (typeof ThePayApi.handlers[action] === 'undefined') {
        ThePayApi.handlers[action] = {};
    }
    ThePayApi.handlers[action][eventName] = callback;
};

ThePayApi.unregisterActionEvent = function(action, eventName) {
    delete ThePayApi.handlers[action][eventName];
};

require('./thepay.api.payments');

ThePayApi.init();
