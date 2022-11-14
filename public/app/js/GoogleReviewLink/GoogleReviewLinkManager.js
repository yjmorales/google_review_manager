'use strict';

/**
 * Class responsible to manage a Google Review Link.
 * @constructor
 */
function GoogleReviewLinkManager() {
    /**
     * Holds the GUI DOM References.
     * @type {Object}
     */
    const ui = {
        $urls: $('#urls'),
        // Buttons
        $btnRemoveReview: $('#btnSubmitRemoveReview'),
        $btnOpenUpdateReviewConfirmation: '[data-id="btn-open-update-review-confirmation"]',
        $btnUpdateReview: $('#btnSubmitUpdateReview'),
        $btnOpenRemoveReviewConfirmation: '[data-id="btn-open-remove-review-confirmation"]',
        $btnOpenRemoveAllConfirmation: $('#btnRemoveAllReviews'),
        $btnOpenSendReviewByEmailConfirmation: $('#btnOpenSendReviewByEmailConfirmation'),
        $btnRemoveSelectedReview: $('#btnRemoveSelectedReview'),
        $btnSubmitRemoveMultipleReviews: $('#btnSubmitRemoveMultipleReviews'),
        $btnSubmitRemoveAllReviews: $('#btnSubmitRemoveAllReviews'),
        $btnSubmitSendReviewByEmail: '#btnSubmitSendReviewByEmail',
        // Modals & Containers
        $modalSendReviewByEmail: $('#modalSendReviewByEmail'),
        $modalRemoveReviewItem: '#modalRemoveReviewItem',
        $modalRemoveMultipleReviewsItem: '#modalRemoveMultipleReviewsItem',
        $modalRemoveAllReviewsItem: '#modalRemoveAllReviewsItem',
        $modalUpdateReviewItem: '#modalUpdateReviewItem', // Containers
        $reviewDetails: '.review-details',
        // Fields
        $fieldReviewLink: 'input[name="review-link"]',
        $fieldCheckboxReviewSelector: '.fieldCheckboxReviewSelector',
        $emptyReviewListLabel: $('#emptyReviewListLabel'),
        $fieldMultipleEmailsToSendReview: 'fieldMultipleEmailsToSendReview',
    }

    /**
     * Holds the class state depending on the GUI Events. Also holds one-time instantiated objects.
     * @type {Object}
     */
    const state = {
        config: {
            $trigger: null,
            $loading: null,
            $callback: null,
            $reviewContainer: null
        },
        modules: {
            Notification: new Notification(),
            LoaderManager: new LoaderManager(),
            MultipleEmailsField: new MultipleEmailsField(),
        },
        clicked: {
            btnRemoveReview: null,
            multipleReviewIdsToRemove: [],
            urlRemoveReview: null,
            btnUpdateReview: null,
            urlUpdateReview: null,
        },
        data: {
            dataMultipleEmails: null,
        }
    };

    /**
     * This function initialize the class with the given config. Also subscribes the GUI events.
     *
     * @param {Object} config Holds the initial configuration.
     * @return {void}
     */
    function init(config) {
        if (config.$trigger) {
            state.config.$trigger = config.$trigger;
        }
        if (config.$loading) {
            state.config.$loading = config.$loading;
        }
        if (config.$callback) {
            state.config.$callback = config.$callback;
        }
        if (config.$reviewContainer) {
            state.config.$reviewContainer = config.$reviewContainer;
        }
        listenUiEvents();
    }

    /**
     * Helper function to subscribe the GUI events.
     * @return {void}
     */
    function listenUiEvents() {
        $(document).on('click', state.config.$trigger, generate);
        $(document).on('click', ui.$btnOpenRemoveReviewConfirmation, openRemoveReviewConfirmationModal);
        ui.$btnRemoveReview.on('click', submitRemoveReview);
        $(document).on('click', ui.$btnOpenUpdateReviewConfirmation, openUpdateReviewConfirmationModal);
        $(document).on('change', ui.$fieldCheckboxReviewSelector, activateActionsForSelectedReviews);
        ui.$btnUpdateReview.on('click', submitUpdateReview);
        ui.$btnRemoveSelectedReview.on('click', openRemoveSelectedReviewConfirmationModal)
        ui.$btnSubmitRemoveMultipleReviews.on('click', submitRemoveMultipleReviews);
        ui.$btnSubmitRemoveAllReviews.on('click', submitRemoveAllReviews);
        ui.$btnOpenRemoveAllConfirmation.on('click', openRemoveAllReviewsConfirmationModal);
        ui.$btnOpenSendReviewByEmailConfirmation.on('click', openSendReviewByEmailConfirmationModal);
        $(document).on('click', ui.$btnSubmitSendReviewByEmail, submitSendReviewsByEmail);
    }

    /**
     * Generates a new Google Review Link.
     * @return {void}
     */
    function generate() {
        const $btn = $(this);
        const url = $btn.data('url');
        $btn.prop('disabled', true);
        if (state.config.$loading) {
            state.config.$loading.start();
        }
        fetch(url, {method: 'POST'})
            .then((response) => response.json())
            .then((data) => {
                if (state.config.$loading) {
                    state.config.$loading.stop();
                }
                $btn.prop('disabled', false);
                const containerByTrigger = $btn.data('review-container');
                const html = getReviewHtml(data.review);
                if (containerByTrigger) {
                    $(containerByTrigger).prepend(html);
                } else if (state.config.$reviewContainer) {
                    $(state.config.$reviewContainer).prepend(html);
                }
                if (state.config.$callback) {
                    state.config.$callback();
                }
                state.modules.Notification.success('The Google Review Link has been generated successfully.', 'Google Review Generation');
                setReviewNumberLabel();
            }).catch((e) => {
            console.debug(e)
            state.modules.Notification.error('Unable to generate the new Google Review Link.', 'Google Review Generation');
        });
    }

    /**
     * Renders the given review object into the review list.
     *
     * @param {object} review Review object holding the information to render.
     * @return {string}
     */
    function getReviewHtml(review) {
        return `
        <div class="card review-details" data-id="review-details" data-review-id="${review.id}">
            <div class="p-2">
                <input 
                type="checkbox"
                class="fieldCheckboxReviewSelector"
                data-review-id="${review.id}"
                 >
            </div>
            <div class="text-center">
                <img class="img-responsive" src="${review.qrCodeImgBase64}" height="150" width="150">
            </div>
            <div class="card-body p-2">
                <strong class="d-block font-14">Review link:</strong>
                <p class="card-text font-14"><a href="${review.link}" class="font-14">${review.link}</a></p>
            </div>
            <div class="card-footer p-2">
            <div class="text-right">
                    <button type="button" class="btn btn-outline-info btn-flat btn-xs btnCopyToClipBoard"
                            data-review-link="${review.link}">
                            <i class="fa fa-clipboard"></i>
                            <span class="text-copy-clipboard">Copy link</span>
                    </button>
                    <button type="button" class="btn btn-outline-info btn-flat btn-xs"
                            data-id="btn-open-update-review-confirmation"
                            data-url-update-review="${review.urlUpdateReview}"
                            data-link="${review.link}">
                        <i class="fa fa-pen"></i>
                        Edit
                    </button>
                    <button type="button" class="btn btn-outline-info btn-flat btn-xs"
                            data-id="btn-open-remove-review-confirmation"
                            data-url-remove-review="${review.urlRemoveReview}">
                        <i class="fa fa-trash"></i>
                        Remove
                    </button>
                </div>
            </div>            
        </div>`;
    }

    /**
     * Opens "delete" confirmation modal.
     * @return {void}
     */
    function openRemoveReviewConfirmationModal() {
        const $btn = $(this);
        state.clicked.btnRemoveReview = $btn.closest(ui.$reviewDetails);
        state.clicked.urlRemoveReview = $btn.data('url-remove-review');
        $(ui.$modalRemoveReviewItem).modal('show');
    }

    /**
     * Opens "delete" confirmation modal.
     * @return {void}
     */
    function openRemoveSelectedReviewConfirmationModal() {
        $(ui.$fieldCheckboxReviewSelector).each(function () {
            const $reviewCheckbox = $(this);
            if (!$reviewCheckbox.is(':checked')) {
                return;
            }
            const reviewId = $reviewCheckbox.data('review-id');
            state.clicked.multipleReviewIdsToRemove.push(reviewId);
        });
        $(ui.$modalRemoveMultipleReviewsItem).modal('show');
    }

    /**
     * Opens 'remove all' confirmation modal.
     * @return {void}
     */
    function openRemoveAllReviewsConfirmationModal() {
        $(ui.$modalRemoveAllReviewsItem).modal('show');
    }

    /**
     * Opens 'send by email' confirmation modal.
     * @return {void}
     */
    function openSendReviewByEmailConfirmationModal() {
        const $modal = $(ui.$modalSendReviewByEmail);
        const multipleEmailsOptions = {
            id: ui.$fieldMultipleEmailsToSendReview
        };
        const dataMultipleEmails = state.modules.MultipleEmailsField.initMultipleEmailField(multipleEmailsOptions);
        state.data.dataMultipleEmails = dataMultipleEmails;
        $modal.modal('show');
        $modal.find('[data-business-email="true"]').text($(this).data('business-email'));
    }

    /**
     * Removes a review from the review list.
     * @return {void}
     */
    function submitRemoveReview() {
        const $btn = $(this);
        $btn.prop('disabled', true);
        state.modules.LoaderManager.startOverlay();
        fetch(state.clicked.urlRemoveReview, {method: 'POST'})
            .then((response) => {
                return response.json();
            })
            .then(() => {
                state.clicked.btnRemoveReview.remove();
                $(ui.$modalRemoveReviewItem).modal('hide');
                state.modules.Notification.success('Google Review Link removed successfully', 'Google Review Link');
                setReviewNumberLabel();
            })
            .catch((e) => {
                state.modules.Notification.error('Unable to remove the selected Google Review Link', 'Google Review Link');
            })
            .finally(() => {
                state.modules.LoaderManager.stopOverlay();
                $btn.prop('disabled', false);
            });
    }

    /**
     * Function used to whether set an empty label or not about reviews.
     */
    function setReviewNumberLabel() {
        if (!$(ui.$reviewDetails).length) {
            ui.$emptyReviewListLabel.removeClass('d-none');
        } else {
            ui.$emptyReviewListLabel.addClass('d-none');
        }
    }

    /**
     * Removes multiples reviews from the review list.
     * @return {void}
     */
    function submitRemoveMultipleReviews() {
        const $btn = $(this);
        const url = $btn.data('url');
        $btn.prop('disabled', true);
        state.modules.LoaderManager.startOverlay();
        const data = new FormData();
        data.append('reviews', JSON.stringify(state.clicked.multipleReviewIdsToRemove));
        fetch(url, {method: 'POST', body: data})
            .then((response) => {
                return response.json();
            })
            .then((data) => {
                data.reviews.forEach((review) => {
                    $(ui.$reviewDetails).each(function () {
                        const $this = $(this);
                        if ($this.data('review-id') === review.id) {
                            $this.remove();
                        }
                    });
                });
                setReviewNumberLabel();
                $(ui.$modalRemoveMultipleReviewsItem).modal('hide');
                state.modules.Notification.success('The selected Google Review Links were removed successfully', 'Google Review Link');
            })
            .catch((e) => {
                state.modules.Notification.error('Unable to remove the selected Google Review Links', 'Google Review Link');
            })
            .finally(() => {
                state.modules.LoaderManager.stopOverlay();
                $btn.prop('disabled', false);
            });
    }

    /**
     * Removes multiples reviews from the review list.
     * @return {void}
     */
    function submitRemoveAllReviews() {
        const $btn = $(this);
        const url = $btn.data('url');
        $btn.prop('disabled', true);
        state.modules.LoaderManager.startOverlay();
        fetch(url, {method: 'POST'})
            .then((response) => {
                return response.json();
            })
            .then(() => {
                $(ui.$reviewDetails).remove();
                setReviewNumberLabel();
                $(ui.$modalRemoveAllReviewsItem).modal('hide');
                state.modules.Notification.success('All Google Review Links were removed successfully', 'Google Review Link');
            })
            .catch((e) => {
                state.modules.Notification.error('Unable to remove all Google Review Links', 'Google Review Link');
            })
            .finally(() => {
                state.modules.LoaderManager.stopOverlay();
                $btn.prop('disabled', false);
            });
    }

    /**
     * Opens "update" confirmation modal.
     * @return {void}
     */
    function openUpdateReviewConfirmationModal() {
        const $btn = $(this);
        state.clicked.btnUpdateReview = $btn.closest(ui.$reviewDetails);
        state.clicked.urlUpdateReview = $btn.data('url-update-review');
        $(ui.$fieldReviewLink).val($btn.data('link'));
        $(ui.$modalUpdateReviewItem).modal('show');
    }

    /**
     * Updates a review
     * @return {void}
     */
    function submitUpdateReview() {
        const $btn = $(this);
        $btn.prop('disabled', true);
        state.modules.LoaderManager.startOverlay();
        const formData = new FormData();
        formData.append('link', $(ui.$fieldReviewLink).val());
        fetch(state.clicked.urlUpdateReview, {
            method: 'POST',
            body: formData,
        })
            .then((response) => response.json())
            .then((data) => {
                const review = data.review;
                const html = getReviewHtml(review);
                const id = `[data-review-id="${review.id}"]`;
                $(id).replaceWith(html);
                $btn.closest('.modal').modal('hide');
                state.modules.Notification.success('The Google Review Link was successfully updated.', 'Google Review Link');
            })
            .catch((e) => {
                console.debug(e)
                state.modules.Notification.error('Unable to update the selected Google Review Link', 'Google Review Link');
            })
            .finally(() => {
                state.modules.LoaderManager.stopOverlay();
                $btn.prop('disabled', false);
            });
    }

    /**
     * Once a review is selected by clicking the checkbox this activates some actions (buttons) that only
     * should be active whenever at least there are 1 selected review.
     */
    function activateActionsForSelectedReviews() {
        let atLeastOnceSelected = 0;
        $(ui.$fieldCheckboxReviewSelector).each(function () {
            if (atLeastOnceSelected) {
                return;
            }
            atLeastOnceSelected = $(this).is(':checked');
        });
        ui.$btnRemoveSelectedReview.prop('disabled', !atLeastOnceSelected);
        ui.$btnOpenSendReviewByEmailConfirmation.prop('disabled', !atLeastOnceSelected);
    }

    /**
     * Once the user clicks and accepts to send a review via email this function perform the process to send the email.
     * @return {void}
     */
    function submitSendReviewsByEmail() {
        if (!state.modules.MultipleEmailsField) {
            throw 'Unable to get the other receivers';
        }
        const $btn = $(this)
            , url = $btn.data('url')
            , otherReceivers = state.data.dataMultipleEmails.getEmailsList()
            , reviewIds = [];
        state.modules.LoaderManager.startOverlay();
        $btn.prop('disabled', true);
        $(ui.$fieldCheckboxReviewSelector).each(function () {
            const $this = $(this);
            if (!$this.is(':checked')) {
                return;
            }
            reviewIds.push($this.data('review-id'));
        });
        const data = new FormData();
        data.append('otherReceivers', JSON.stringify(otherReceivers));
        data.append('reviewIds', JSON.stringify(reviewIds));
        fetch(url, {method: 'POST', body: data})
            .then((response) => response.json())
            .then((data) => {
                if (200 > data.status || data.status > 299) {
                    throw 'Error';
                }
                state.modules.Notification.success(`The selected reviews have been successfully sent via email.`, 'Email notification');
            })
            .catch((e) => {
                console.debug(e)
                state.modules.Notification.error('Unable to send the email.', 'Email notification');
            })
            .finally(() => {
                $btn.prop('disabled', false);
                state.modules.LoaderManager.stopOverlay();
            });
    }

    this.init = init;
    this.getReviewHtml = getReviewHtml;
    this.setReviewNumberLabel = setReviewNumberLabel;
}