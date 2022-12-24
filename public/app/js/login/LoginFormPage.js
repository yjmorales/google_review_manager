'use strict';

/**
 * Manages the login form page.
 */
function LoginFormPage() {

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
    function init() {
        (new LoginFormValidator()).init({callback: login});
    }

    /**
     * Submits the login form once all is good.
     * @param {jquery} $form Form to login the user.
     * @return {void}
     */
    function login($form) {
        $form.submit();
    }

    this.init = init;
}