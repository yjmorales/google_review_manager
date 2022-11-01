'use strict';

/**
 * Controls the form elements. It must be initialized by 'new FormManagement()'
 */
function FormManagement() {

    const state = {
        modules: {
            loaderManager: new LoaderManager(),
        },
    };

    // UI Elements.
    const ui = {
        $btnClearField: $('button[data-clear-field="true"]'),
        $btnClearForm: $('button[data-clear-form="true"]'),
        $form: 'form',
    };

    /**
     * Front controller of the class.
     */
    function init() {
        // Clear the form fields
        ui.$btnClearField.on('click', clearField);
        ui.$btnClearForm.on('click', clearForm);
        $(document).on('submit', ui.$form, showLoader);
    }

    /**
     * Clear the form fields.
     */
    function clearField() {
        const $inputGroup = $(this).closest('.input-group');
        $inputGroup.find('input').val(null);
        $inputGroup.find('textarea').val(null);
        $inputGroup.find('select').val(null);
    }

    /**
     * Clear the whole form fields.
     */
    function clearForm() {
        const $form = $(this).closest('form');
        $form.find('input').val(null);
        $form.find('textarea').val(null);
        $form.find('select').val(null);
        $form.find('.yjmSelect2').val(null).trigger('change');
    }

    /**
     * Shows the overlay loader while the form is submitted.
     */
    function showLoader() {
        state.modules.loaderManager.startOverlay();
    }

    init();
}