'use strict';

/**
 * Controls the form elements. It must be initialize by 'new FormManagement()'
 */
function FormManagement() {

    // UI Elements.
    const ui = {
        $btnClearForm: $('button[data-clear-form="true"]'),
        $toggleField: $('input[data-toggle="true"]'),

    };

    /**
     * Front controller of the class.
     */
    function init() {
        // Clear the form fields
        ui.$btnClearForm.on('click', clearForm);
    }

    /**
     * Clear the form fields
     */
    function clearForm() {
        const $form = $(this).closest('form');
        $form.find('input').val(null);
        $form.find('textarea').val(null);
        $form.find('select').val(null);
    }

    init();
}