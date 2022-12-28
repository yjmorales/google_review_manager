'use strict';

/**
 * Validates the `user` form to change the password.
 */
function UserFormValidator() {

    /**
     * Holds the UI References.
     * @type {Object}
     */
    const ui = {
        $form: 'form[name="user_form"]',
        $fieldUserId: $('input#userId'),
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
        let rules = {
            'user_form[email]': {
                required: true,
                email: true,
                minlength: 2,
                maxlength: 255,
            },
            'user_form[roles]': {
                required: true,
            },
        };

        let messages = {
            'user_form[email]': {
                required: "Please enter your email.",
                maxlength: "The email must not be longer than 255 characters long.",
                minlength: "The email must not be lesser than 2 characters long.",
            },
            'user_form[roles]': {
                required: "Please select a role.",
            },
        };

        const isEdit = Boolean(ui.$fieldUserId.val());
        if (!isEdit) {
            Object.assign(rules, {
                'rules.user_form[password]': {
                    required: true,
                    password: true,
                    minlength: 2,
                    maxlength: 255,
                }
            });
            Object.assign(messages, {
                'messages.user_form[password]': {
                    required: "Please enter your password.",
                    maxlength: "The password must not be longer than 255 characters long.",
                    minlength: "The password must not be lesser than 2 characters long.",
                }
            });
        }

        $(ui.$form).validate(
            {
                rules: rules,
                messages: messages,
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