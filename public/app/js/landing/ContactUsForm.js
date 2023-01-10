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


        // First verifies that the submission is not a robot. Via ReCaptcha v3
        const $btn = ui.$btnContactUsSubmit;
        const siteKey = $btn.data('sitekey');
        grecaptcha.ready(function () {
            grecaptcha.execute(siteKey, {action: 'submit'})
                .then(function (token) {
                    // Once the token is got the following submits the data to send contact message. The token
                    // should be passed.
                    token = token.replace('\n', '');

                    const $spinner = $btn.find('.btn-spinner');
                    $btn.prop('disabled', true);
                    $spinner.removeClass('d-none');
                    ui.$form.find('input').attr('readonly', true);
                    ui.$form.find('textarea').attr('readonly', true);

                    const data = new FormData();
                    data.append('name', ui.$name.val());
                    data.append('email', ui.$email.val());
                    data.append('subject', ui.$subject.val());
                    data.append('message', ui.$message.val());
                    data.append('g-recaptcha-response', token);

                    const url = $btn.data('url');
                    fetch(url, {method: 'post', body: data})
                        .then((response) => {
                            if (!response.ok) {
                                throw 'Unable to send the message';
                            }
                            return response.json()
                        })
                        .then((data) => {
                            state.modules.Notification.success(`Thank you for contacting us. Our team will read your comment and if necessary we will contact you.`, 'Message sent.');
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
                            $btn.prop('disabled', false);
                            $spinner.addClass('d-none');
                            ui.$form.find('input').attr('readonly', false);
                            ui.$form.find('textarea').attr('readonly', false);
                        });
                });
        });
    }

    this.init = init;
}