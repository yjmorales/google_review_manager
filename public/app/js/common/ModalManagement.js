'use strict';

/**
 * Manages the modal component.
 */
function ModalManagement() {
    /**
     * Holds the modal management state.
     * @type {Object}
     */
    const state = {
        config: {
            id: null, triggerSelector: null, title: null, body: null, successCallback: null, cancelCallback: null,
        },
    }

    /**
     * Holds the GUI references.
     * @type {Object}
     */
    const ui = {
        $title: '[data-id="modal-title"]',
        $body: '[data-id="modal-body"]',
        $btnOk: '[data-id="modal-ok-button"]',
        $btnCancel: '[data-id="modal-cancel-button"]',
    };

    /**
     * Initialize the modal manager class.
     * @param {Object} args Arguments used to initialize the module.
     */
    function init(args) {
        Object.assign(state.config, args);
        if (!state.config.id) {
            throw 'No modal id has been given.';
        }
        updateGui();
        subscribeUIEvents();
    }

    /**
     * This function subscribes the GUI components to the events.
     */
    function subscribeUIEvents() {
        $(state.config.triggerSelector).on('click', open);
    }

    /**
     * This function opens the modal.
     */
    function open() {
        $(state.config.id).modal('show');
    }

    /**
     * This function updates the GUI elements based on the configuration.
     * Edit title, body, success and cancel callback properly.
     */
    function updateGui() {
        if (state.config.title) {
            $(ui.$title).text(state.config.title);
        }
        if (state.config.body) {
            $(ui.$body).html(state.config.body);
        }
        if (state.config.successCallback) {
            $(ui.$btnOk).on('click', state.config.successCallback);
        }
        if (state.config.cancelCallback) {
            $(ui.$btnCancel).on('click', state.config.cancelCallback);
        }
    }

    this.init = init;
}