
let helpers = {
    /**
     * Returns and try to parse data attribute to javascript variable
     * @param DOMnode element DOM element
     * @param String key
     * @param defValue
     * @returns {any}
     */
    elementGetData: function (element, key, defValue) {
        let value = (typeof element.getAttribute !== 'undefined') ? element.getAttribute('data-' + key) : null;
        if ((value === null) || (value === "")){
            return defValue;
        }
        try {
            return JSON.parse(value);
        }
        catch(err){
            return value;
        }
    },

    /**
     * Returns true if data attribute exists in element
     * @param DOMnode element
     * @param String key
     * @returns {boolean}
     */
    elementHasData : function(element, key) {
        let value = (typeof element.getAttribute !== 'undefined') ? element.getAttribute('data-' + key) : null;
        return (value !== null) && (value !== "");
    },

    /**
     * Returns parent node element with data attribute by dataKey. Returns null if nothing was found.
     * @param DOMnode target Child node
     * @param String dataKey data key which we looking for
     * @returns {DOMNode|null} Return DOMnode or null.
     */
    elementParentByData(target, dataKey){
        let parent = target.parentNode;
        while(parent){
            if (helpers.elementHasData(parent, dataKey)){
                return parent;
            }
            parent = (typeof parent.parentNode !== 'undefined') ? parent.parentNode : false;
        }
        return null;
    }
};

module.exports = helpers;
