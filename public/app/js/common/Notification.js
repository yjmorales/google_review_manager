'use strict';

/**
 * Module responsible to displays Swal notifications on the GUI
 */
function Notification() {
    /**
     * GUI references.
     * @type {Object}
     */
    const ui = {
        $notificationsSuccess: '.notifications-success',
        $notificationsError: '.notifications-error',
        $notificationsWarning: '.notifications-warning',
    };

    /**
     * Subscribes GUI Events.
     */
    function listenUIEvents() {
        $(document).ready(notifyFlashMessages);
    }

    /**
     * Function used to displays the respective notification if symfony flash messages have been created.
     * This is done once the page is loaded.
     */
    function notifyFlashMessages() {
        $(ui.$notificationsSuccess).each(function () {
            const msg = $(this).text();
            success(msg);
        });
        $(ui.$notificationsError).each(function () {
            const msg = $(this).text();
            error(msg);
        });
        $(ui.$notificationsWarning).each(function () {
            const msg = $(this).text();
            warning(msg);
        });
    }

    /**
     * Displays an error notification.
     *
     * @param {string} message Error message.
     * @param {string|null} title Dialog title
     */
    function error(message, title = null) {
        const prop = commonProperties(message, title);
        const Toast = Swal.mixin(prop.structure);
        Toast.fire(Object.assign(prop.style, {icon: 'error', background: '#FFE7E7',}));
    }

    /**
     * Displays a success notification.
     *
     * @param {string} message Success message.
     * @param {string|null} title Dialog title
     */
    function success(message, title = null) {
        const prop = commonProperties(message, title);
        const Toast = Swal.mixin(prop.structure);
        Toast.fire(Object.assign(prop.style, {icon: 'success', background: '#f5fef0',}));
    }

    /**
     * Displays a warning notification.
     *
     * @param {string} message warning message.
     * @param {string|null} title Dialog title
     */
    function warning(message, title = null) {
        const prop = commonProperties(message, title);
        const Toast = Swal.mixin(prop.structure);
        Toast.fire(Object.assign(prop.style, {icon: 'warning', background: '#FFFCE7',}));
    }

    /**
     * Helper function to build the common properties for render all type of notification.
     *
     * @param {string} message warning message.
     * @param {string|null} title Dialog title
     */
    function commonProperties(message, title) {
        return {
            structure: {
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 5000
            },
            style: {
                title: title ?? false,
                html: message,
                allowEscapeKey: true,
                showCloseButton: true,
                closeButtonHtml: '<i class="fa-solid fa-xmark font-14" role="button" title="Close"></i>',
            },
        }
    }

    this.success = success;
    this.error = error;
    this.warning = warning;
    this.listenUIEvents = listenUIEvents;
}