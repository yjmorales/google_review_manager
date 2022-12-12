'use strict';

/**
 * Manages a component to enter multiple emails
 */
function MultipleEmailsField() {

    // UI Elements.
    const ui = {
        $field: 'div[data-multiple-emails="true"]',
        $fieldEmail: '.email-block',
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

    /**
     * Removes all added emails.
     * @return {void}
     */
    function clear() {
        $(ui.$fieldEmail).remove();
    }

    this.initMultipleEmailField = initMultipleEmailField;
    this.clear = clear;
}