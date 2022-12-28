'use strict';

/**
 * Validates the `get email` form to change the password.
 */
function ChangePasswordGetEmailFormValidator() {

    /**
     * Holds the UI References.
     * @type {Object}
     */
    const ui = {
        $form: 'form#formChangePasswordGetEmail',
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
     * Validates the `get email` form.
     */
    function validate() {
        $(ui.$form).validate(
            {
                rules: {
                    'formChangePasswordGetEmail[email]': {
                        required: true,
                        email: true,
                        minlength: 2,
                        maxlength: 255,
                    },
                },
                messages: {
                    'formChangePasswordGetEmail[email]': {
                        required: "Please enter your email.",
                        maxlength: "The email must not be longer than 255 characters long.",
                        minlength: "The name must not be lesser than 2 characters long.",
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