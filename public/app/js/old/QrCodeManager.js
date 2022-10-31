'use strict';

/**
 * Manages Qr codes.
 */
function QrCodeManager() {

    /**
     * @type {Object} Holds the module state.
     */
    const state = {
        modules: {
            Notification: new Notification(),
            ModalManager: new ModalManagement(),
        }, clicked: {
            qrDetailsToRemove: null,
        }
    }
    /**
     * UI Elements.
     */
    const ui = {
        // Buttons
        $btnRemoveQrCodeConformation: '[data-id="btn-remove-qr-code"]',
        // ! Buttons

        // Form containers and dialogs.
        $qrCodeDetails: 'data-id="qr-code-details"',
        $modalRemoveQrCodeConfirmation: '#modalDeleteListItem',
        // ! Form containers

    };

    /**
     * Subscribes UI Events.
     */
    function listenUIEvents() {
        $(document).on('click', ui.$btnRemoveQrCodeConformation, initModalRemoveQrCode);
    }

    function initModalRemoveQrCode() {
        const settings = {
            id: ui.$modalRemoveQrCodeConfirmation,
            title: 'Remove Qr Code',
            body: `<p>You selected to remove the QR code respective a Google Review. Once you accept this action cannot be undo.</p><p>Do you accept to remove it?</p>` ,
            successCallback: removeQrCode,
        };
        state.modules.ModalManager.init(settings);
        $(ui.$modalRemoveQrCodeConfirmation).modal('show');
        state.clicked.qrDetailsToRemove = $(this).closest(`[${ui.$qrCodeDetails}]`);
        console.debug(state.clicked.qrDetailsToRemove)
    }


    /**
     * Removes a business row.
     */
    function removeQrCode() {
        try {
            // todo: call to the endpoint
            const message = `Qr code successfully removed`;
            state.modules.Notification.success(message);
            $(ui.$modalRemoveQrCodeConfirmation).modal('hide');
            console.debug(state.clicked.qrDetailsToRemove)
            state.clicked.qrDetailsToRemove.remove();
        }
        catch (e) {
            state.modules.Notification.error('Unable to remove the Qr Code respective to the review.');
        }
    }

    /**
     * Function used to initialize the module.
     */
    function init() {
        listenUIEvents();
    }


    this.init = init;
}