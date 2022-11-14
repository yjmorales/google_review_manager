'use strict';

/**
 * Manages a component to enter multiple emails
 */
function MultipleEmailsField() {

    // UI Elements.
    const ui = {
        $field: 'div[data-multiple-emails="true"]',
    };

    /**
     * Once the page is loaded it initializes multiple emails field.
     * @param {Object} options Options to build the Multiple Email component.
     * @return {Object}
     */
    function initMultipleEmailField(options) {
        const inputContainerNode = document.querySelector(ui.$field);
        return EmailsInput(inputContainerNode, options ?? {});
    }

    this.initMultipleEmailField = initMultipleEmailField;
}