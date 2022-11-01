'use strict';

/**
 * Manages business under create scenarios.
 */
function BusinessCreate() {

    /**
     * @type {Object} Holds the module state.
     */
    const state = {
        modules: {
            LoaderManager: new LoaderManager(),
            BusinessFormValidator: new BusinessFormValidator(),
        }
    }
    /**
     * UI Elements.
     */
    const ui = {
        $form: $('form[name="business_form"]'),
    };

    /**
     * This function is a proxy between the form validation module and the browser. It triggers the form submission
     * whenever the validation is success.
     */
    function submit(form) {
        state.modules.LoaderManager.startOverlay();
        form.submit();
    }

    /**
     * Function used to initialize the module.
     * @return {void}
     */
    function init() {
        const validationConfig = {callback: submit,};
        state.modules.BusinessFormValidator.init(validationConfig);
    }

    this.init = init;
}