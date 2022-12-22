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
     * @param {int|null} timer Timer to hide the notification
     */
    function error(message, title = null, timer = null) {
        const prop = commonProperties(message, title, timer);
        const Toast = Swal.mixin(prop.structure);
        Toast.fire(Object.assign(prop.style, {icon: 'error', background: '#FFE7E7',}));
    }

    /**
     * Displays a success notification.
     *
     * @param {string} message Success message.
     * @param {string|null} title Dialog title
     * @param {int|null} timer Holds the timer used to hide the notification.
     */
    function success(message, title = null, timer = null) {
        const prop = commonProperties(message, title, timer);
        const Toast = Swal.mixin(prop.structure);
        Toast.fire(Object.assign(prop.style, {icon: 'success', background: '#f5fef0',}));
    }

    /**
     * Displays a warning notification.
     *
     * @param {string} message warning message.
     * @param {string|null} title Dialog title
     * @param {int|null} timer Timer to hide the notification
     */
    function warning(message, title = null, timer = null) {
        const prop = commonProperties(message, title);
        const Toast = Swal.mixin(prop.structure);
        Toast.fire(Object.assign(prop.style, {icon: 'warning', background: '#FFFCE7',}));
    }

    /**
     * Helper function to build the common properties for render all type of notification.
     *
     * @param {string} message warning message.
     * @param {string|null} title Dialog title
     * @param {int|null} timer Timer to hide the notification
     */
    function commonProperties(message, title, timer = null) {
        return {
            structure: {
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: timer ?? 5000
            },
            style: {
                title: title ?? false,
                html: message,
                allowEscapeKey: true,
                showCloseButton: true,
                closeButtonHtml: '<i class="fa-solid fa-xmark font-14 text-black" role="button" title="Close"></i>',
            },
        }
    }

    this.success = success;
    this.error = error;
    this.warning = warning;
    this.listenUIEvents = listenUIEvents;
}