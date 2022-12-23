'use strict';

/**
 * Holds the logic to send a Contact Us message.
 */
function ContactUsForm() {
    /**
     * UI References
     * @type {Object}
     */
    const ui = {
        $btnContactUsSubmit: $('#btnContactUsSubmit'),
        $form: $('form#form_contact_us'),
        // Fields
        $name: $('form#form_contact_us input[name="name"]'),
        $email: $('form#form_contact_us input[name="email"]'),
        $subject: $('form#form_contact_us input[name="subject"]'),
        $message: $('form#form_contact_us textarea[name="message"]'),
    }

    const state = {
        modules: {
            Notification: new Notification(),
        }
    }

    /**
     * Initializes the modules.
     */
    function init() {
        (new ContactUsFormValidator()).init({callback: sendMessage});
        ui.$form.find('input').val(null);
        ui.$form.find('textarea').val(null);
    }

    /**
     * Holds the logic to send the message.
     */
    function sendMessage() {
        ui.$btnContactUsSubmit.prop('disabled', true);
        const url = ui.$btnContactUsSubmit.data('url');
        const data = new FormData();
        data.append('name', ui.$name.val());
        data.append('email', ui.$email.val());
        data.append('subject', ui.$subject.val());
        data.append('message', ui.$message.val());
        fetch(url, {method: 'post', body: data})
            .then((response) => {
                if (!response.ok) {
                    throw 'Unable to send the message';
                }
                return response.json()
            })
            .then((data) => {
                state.modules.Notification.success(`Thank you for get in touch with us.`, 'Message sent.');
                ui.$name.val(null);
                ui.$subject.val(null);
                ui.$email.val(null);
                ui.$message.val(null);
            })
            .catch((e) => {
                console.debug(e);
                state.modules.Notification.error(`Unable to send the message.`, 'Message was not sent.');
            })
            .finally(() => {
                ui.$btnContactUsSubmit.prop('disabled', false);
            });
    }

    this.init = init;
}