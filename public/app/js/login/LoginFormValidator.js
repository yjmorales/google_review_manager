'use strict';

/**
 * Validator for the login form.
 */
function LoginFormValidator() {

    /**
     * Holds the UI References.
     * @type {Object}
     */
    const ui = {
        $form: 'form#form_login',
    };

    /**
     * Initializes the module.
     */
    function init(config) {
        if (!config.callback) {
            throw 'No callback to execute when form is valid.';
        }
        $.validator.setDefaults({submitHandler: config.callback});
        validate();
    }

    /**
     * Validates login form.
     */
    function validate() {
        $(ui.$form).validate(
            {
                rules: {
                    'form_login[_username]': {
                        required: true,
                        email: true,
                        minlength: 2,
                        maxlength: 255,
                    },
                    'form_login[_password]': {
                        required: true,
                        minlength: 2,
                        maxlength: 255,
                    },
                },
                messages: {
                    'form_login[_username]': {
                        required: "Please enter your email.",
                        maxlength: "The email must not be longer than 255 characters long.",
                        minlength: "The name must not be lesser than 2 characters long.",
                    },
                    'form_login[_password]': {
                        required: "Please enter a password.",
                        maxlength: "The password must not be longer than 255 characters long.",
                        minlength: "The password must not be lesser than 2 characters long.",
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