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
            LoaderManager: new LoaderManager(),
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
        $btnGenerateReviewOnEditForm: $('#btnGenerateReviewOnEditForm'),
        // Modals
        $modalRemoveReviewItem: '#modalRemoveReviewItem',
    };

    /**
     * Subscribes GUI Events.
     */
    function listenUIEvents() {
        $(document).on('click', ui.$btnOpenRemoveReviewConfirmation, openRemoveReviewConfirmationModal);
        ui.$btnRemoveReview.on('click', submitRemoveReview);
        ui.$btnGenerateReviewOnEditForm.on('click', generateReview);
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
     * Generates a new review.
     */
    function generateReview() {
        const $btn = $(this);
        $btn.prop('disabled', true);
        state.modules.LoaderManager.startDotLoader();
        // todo: generate in BO
        setTimeout(function () {
            state.modules.LoaderManager.stopDotLoader();
            $btn.prop('disabled', false);
        }, 2000);
    }

    /**
     * Function used to initialize the module.
     */
    function init() {
        listenUIEvents();
    }

    this.init = init;
}