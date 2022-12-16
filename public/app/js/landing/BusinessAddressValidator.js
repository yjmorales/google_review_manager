'use strict';

/**
 * Manages the business form validation held by Landing Page.
 */
function BusinessAddressValidator() {
    /**
     * Holds the UI References.
     * @type {Object}
     */
    const ui = {
        $form: $('form#form-landing-business-info'),
    };

    /**
     * Initializes the module.
     */
    function init(config) {
        if (!config.callback) {
            throw 'No callback to execute when form is valid.';
        }
        const defaults = {submitHandler: config.callback};
        $.validator.setDefaults(defaults);
        validate();
    }

    /**
     * Validates the form.
     * @link: https://jqueryvalidation.org/
     * @return {void}
     */
    function validate() {
        ui.$form.validate(
            {
                rules: {
                    'business_form[address]': {
                        required: true,
                        minlength: 2,
                        maxlength: 255,
                    },
                },
                messages: {
                    'business_form[address]': {
                        required: "Please enter a business address.",
                        minlength: "The address must be at least 2 characters long.",
                        maxlength: "The address name must not be longer than 255 characters long.",
                    },
                },
                errorElement: 'span',
                errorPlacement: function (error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                },
                highlight: function (element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function (element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                }
            }
        );
    }

    this.init = init;
}