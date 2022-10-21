'use strict';

/**
 * Module responsible to displays Swal notifications on the GUI
 */
function Notification() {
    /**
     * Displays an error notification.
     *
     * @param {string} message Error message.
     */
    function error(message) {
        const Toast = Swal.mixin(
            {
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 5000
            });
        Toast.fire(
            {
                icon: 'error',
                title: message
            });
    }

    /**
     * Displays a success notification.
     *
     * @param {string} message Success message.
     */
    function success(message) {
        const Toast = Swal.mixin(
            {
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 5000
            });
        Toast.fire(
            {
                icon: 'success',
                title: message
            });
    }

    this.success = success;
    this.error = error;
}