'use strict';

/**
 * Validator for the contact us section  located on the Google review generator form - Landing Page.
 */
function ContactUsFormValidator() {

    /**
     * Holds the UI References.
     * @type {Object}
     */
    const ui = {
        $form: 'form#form_contact_us',
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
     * Validates contact form.
     */
    function validate() {
        $(ui.$form).validate(
            {
                rules: {
                    'form_contact_us[name]': {
                        required: false,
                        minlength: 2,
                        maxlength: 255,
                    },
                    'form_contact_us[email]': {
                        required: true,
                        email: true,
                        minlength: 2,
                        maxlength: 255,
                    },
                    'form_contact_us[subject]': {
                        required: false,
                        minlength: 2,
                        maxlength: 255,
                    },
                    'form_contact_us[message]': {
                        required: true,
                        minlength: 2,
                        maxlength: 500,
                    },
                },
                messages: {
                    'form_contact_us[name]': {
                        required: "Please enter a contact name.",
                        maxlength: "The contact name must not be longer than 255 characters long.",
                        minlength: "The contact name must not be lesser than 2 characters long.",
                    },
                    'form_contact_us[email]': {
                        required: "Please enter a contact email.",
                        maxlength: "The contact email must not be longer than 255 characters long.",
                        minlength: "The contact name must not be lesser than 2 characters long.",
                    },
                    'form_contact_us[subject]': {
                        required: "Please enter a subject.",
                        maxlength: "The subject must not be longer than 255 characters long.",
                        minlength: "The subject must not be lesser than 2 characters long.",
                    },
                    'form_contact_us[message]': {
                        required: "Please enter a message.",
                        maxlength: "The message must not be longer than 500 characters long.",
                        minlength: "The message must not be lesser than 2 characters long.",
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