'use strict';

/**
 * Manages the business form validation
 */
function BusinessFormValidator() {
    /**
     * Holds the UI References.
     * @type {Object}
     */
    const ui = {
        $form: $('form[name="business_form"]'),
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
     * Holds the validation rules.
     */
    function validate() {
        ui.$form.validate(
            {
                rules: {
                    'business_form[name]': {
                        required: true,
                        minlength: 2,
                        maxlength: 255,
                    },
                    'business_form[email]': {
                        required: false,
                        email: true,
                        minlength: 2,
                        maxlength: 255,
                    },
                    'business_form[industrySector]': {
                        required: true
                    },
                    'business_form[address]': {
                        required: true,
                        minlength: 2,
                        maxlength: 255,
                    },
                    'business_form[city]': {
                        required: true,
                        minlength: 2,
                        maxlength: 255,
                    },
                    'business_form[state]': {
                        required: true,
                        minlength: 2,
                        maxlength: 255,
                    },
                    'business_form[zipCode]': {
                        required: true,
                        minlength: 2,
                        maxlength: 15,
                    },
                },
                messages: {
                    'business_form[name]': {
                        required: "Please enter a business name.",
                        minlength: "The business name must be at least 2 characters long.",
                        maxlength: "The business name must not be longer than 255 characters long.",
                    },
                    'business_form[industrySector]': {
                        required: "Please enter an industry sector.",
                    },
                    'business_form[address]': {
                        required: "Please enter a business address.",
                        minlength: "The address must be at least 2 characters long.",
                        maxlength: "The address name must not be longer than 255 characters long.",
                    },
                    'business_form[city]': {
                        required: "Please enter a city.",
                        minlength: "The city name must be at least 2 characters long.",
                        maxlength: "The city name must not be longer than 255 characters long.",
                    },
                    'business_form[state]': {
                        required: "Please enter a state.",
                        minlength: "The state name must be at least 2 characters long.",
                        maxlength: "The state name must not be longer than 255 characters long.",
                    },
                    'business_form[zipCode]': {
                        required: "Please enter a zip code.",
                        minlength: "The zip code must be at least 5 characters long.",
                        maxlength: "The zip code must not be longer than 15 characters long.",
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
            });
    }

    this.init = init;
}