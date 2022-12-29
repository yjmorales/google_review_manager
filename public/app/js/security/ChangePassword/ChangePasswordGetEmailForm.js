'use strict';

/**
 * Module responsible to manage the change password form page.
 */
function ChangePasswordGetEmailForm() {

    const state = {
        modules: {
            Notification: new Notification(),
        }
    };

    /**
     * Holds the UI References.
     * @type {Object}
     */
    const ui = {
        $form: 'form#formChangePasswordGetEmail',
        $formContainer: $('#containerChangePasswordCredential'),
        $email: $('input[name="email"]'),
        $btn: $('button[type="submit"]'),
        $success: $('#changePasswordEmailSentSuccessfully'),
    };

    /**
     * Initializes the module.
     */
    function init() {
        ui.$email.val(null);
        (new ChangePasswordGetEmailFormValidator()).init({callback: submit});
    }

    /**
     * If the form does not have errors this submits the email to generate the link to change the password.
     *
     * @param {jquery} $form Form to login the user.
     * @return {void}
     */
    function submit($form) {
        const email = ui.$email.val();
        const data = new FormData();
        const url = ui.$btn.data('url');
        const $btnSpinner = ui.$btn.find('.btn-spinner');
        ui.$btn.prop('disabled', true);
        $btnSpinner.removeClass('d-none');
        data.append('email', email);
        fetch(url, {method: 'POST', body: data})
            .then((response) => response.json())
            .then((data) => {
                if (200 > data.status || data.status > 299) {
                    throw 'Error';
                }
                ui.$formContainer.remove();
                ui.$success.removeClass('d-none');
            })
            .catch((e) => {
                console.debug(e)
                state.modules.Notification.error('Unable to send the email.', 'Email notification');
            })
            .finally(() => {
                ui.$btn.prop('disabled', false);
                $btnSpinner.addClass('d-none');
            });
    }

    this.init = init;
}