'use strict';

/**
 * Manages business under edit scenarios.
 */
function BusinessEdit() {

    /**
     * @type {Object} Holds the module state.
     */
    const state = {
        modules: {
            Notification: new Notification(),
        }, clicked: {
            btnRemoveReview: null
        }
    }
    /**
     * UI Elements.
     */
    const ui = {
        // Buttons
        $btnOpenRemoveReviewConfirmation: '[data-id="btn-open-remove-review-confirmation"]',
        $btnRemoveReview: $('#btnSubmitRemoveReview'),
        // Modals
        $modalRemoveReviewItem: '#modalRemoveReviewItem',
    };

    /**
     * Subscribes GUI Events.
     */
    function listenUIEvents() {
        $(document).on('click', ui.$btnOpenRemoveReviewConfirmation, openRemoveReviewConfirmationModal);
        ui.$btnRemoveReview.on('click', submitRemoveReview);
    }

    /**
     * Opens "delete" confirmation modal.
     */
    function openRemoveReviewConfirmationModal() {
        const $btn = $(this);
        state.clicked.btnRemoveReview = $btn.closest('[data-id="review-details"]');
        $(ui.$modalRemoveReviewItem).modal('show');
    }

    /**
     * Removes a review from the review list.
     */
    function submitRemoveReview() {
        // todo: call endpoint to remove
        state.clicked.btnRemoveReview.remove();
        $(ui.$modalRemoveReviewItem).modal('hide');
        state.modules.Notification.success('Review removed successfully');
    }

    /**
     * Function used to initialize the module.
     */
    function init() {
        listenUIEvents();
    }

    this.init = init;
}