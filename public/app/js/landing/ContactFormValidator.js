'use strict';

/**
 * Validator for the contact section  located on the Google review generator form - Landing Page.
 */
function ContactFormValidator() {

    /**
     * Holds the UI References.
     * @type {Object}
     */
    const ui = {
        $form: 'form#form-landing-contact-info',
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
                    'contact_form[email]': {
                        required: true,
                        email: true,
                        minlength: 2,
                        maxlength: 255,
                    },
                },
                messages: {
                    'contact_form[email]': {
                        required: "Please enter a contact email.",
                        maxlength: "The contact email must not be longer than 255 characters long.",
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