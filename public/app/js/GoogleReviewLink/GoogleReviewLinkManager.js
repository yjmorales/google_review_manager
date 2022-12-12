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
        $btnOpenRemoveReviewConfirmation: '[data-id="btn-open-remove-review-confirmation"]',
        $btnOpenSendReviewByEmailConfirmation: '#btnOpenSendReviewByEmailConfirmation',
        $btnSubmitSendReviewByEmail: '#btnSubmitSendReviewByEmail',
        // Modals & Containers
        $modalSendReviewByEmail: $('#modalSendReviewByEmail'),
        $modalRemoveReviewItem: '#modalRemoveReviewItem',
        $reviewDetails: '.review-details',
        // Fields
        $fieldReviewLink: 'input[name="review-link"]',
        $emptyReviewListLabel: $('#emptyReviewListLabel'),
        $fieldMultipleEmailsToSendReview: 'fieldMultipleEmailsToSendReview',
        $indicatorSendEmailToOwner: $('#indicatorSendEmailToOwner'),
        $fieldPlaceId: $('#business_form_place'),
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
            reviewIdToSendEmail: null,
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
        $(document).on('click', ui.$btnOpenSendReviewByEmailConfirmation, openSendReviewByEmailConfirmationModal);
        $(document).on('click', ui.$btnSubmitSendReviewByEmail, submitSendReviewsByEmail);
        ui.$modalSendReviewByEmail.on('hidden.bs.modal', resetSendByEmailForm);
    }

    /**
     * Generates a new Google Review Link.
     * @return {void}
     */
    function generate() {
        if (getReviewsCount()) {
            return;
        }
        const $btn = $(this);
        const url = $btn.data('url');
        $btn.prop('disabled', true);
        if (state.config.$loading) {
            state.config.$loading.start();
        }
        const body = new FormData();
        body.append('place_id', ui.$fieldPlaceId.val());
        fetch(url, {method: 'POST', body: body})
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
            <div class="text-center">
                <img class="img-responsive" src="${review.qrCodeImgBase64}" height="150" width="150">
            </div>
            <div class="card-body p-2 text-center">
                <strong class="d-block font-14">Review link:</strong>
                <p class="card-text font-14"><a href="${review.link}" class="font-14">${review.link}</a></p>
            </div>
            <div class="card-footer p-2">
            <div class="text-center">
                    <button type="button" class="btn btn-outline-info btn-flat btn-xs btnCopyToClipBoard"
                            data-review-link="${review.link}">
                            <i class="fa fa-clipboard"></i>
                            <span class="text-copy-clipboard">Copy link</span>
                    </button>
                    <a href="${review.urlDownloadReview}" class="btn btn-xs btn-outline-info">
                        Download
                    </a>
                     <button id="btnOpenSendReviewByEmailConfirmation"
                            role="button"
                            class="btn btn-xs btn-outline-info"
                            data-review-id="${review.id}"
                            data-business-email="${review.businessEmail}"
                            type="button">
                        Send by email
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
     * Opens 'send by email' confirmation modal.
     * @return {void}
     */
    function openSendReviewByEmailConfirmationModal() {
        const $modal = $(ui.$modalSendReviewByEmail);
        const multipleEmailsOptions = {
            id: ui.$fieldMultipleEmailsToSendReview
        };
        if (!state.data.dataMultipleEmails) {
            state.data.dataMultipleEmails = state.modules.MultipleEmailsField.initMultipleEmailField(multipleEmailsOptions);
        }
        state.data.reviewIdToSendEmail = $(this).data('review-id');
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
                showGenerateReviewBtn();
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
            , reviewIds = [state.data.reviewIdToSendEmail]
            , sendToBusiness = ui.$indicatorSendEmailToOwner.is(':checked')
        ;

        state.modules.LoaderManager.startOverlay();
        $btn.prop('disabled', true);
        const data = new FormData();
        data.append('otherReceivers', JSON.stringify(otherReceivers));
        data.append('reviewIds', JSON.stringify(reviewIds));
        data.append('sendToBusiness', JSON.stringify(sendToBusiness));
        fetch(url, {method: 'POST', body: data})
            .then((response) => response.json())
            .then((data) => {
                if (200 > data.status || data.status > 299) {
                    throw 'Error';
                }
                state.modules.Notification.success(`The selected reviews have been successfully sent via email.`, 'Email notification');
                ui.$modalSendReviewByEmail.modal('hide');
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

    /**
     * After the user closes the `send by email` modal this resets the form within the modal.
     */
    function resetSendByEmailForm() {
        ui.$indicatorSendEmailToOwner.prop('checked', true).trigger('change');
        state.modules.MultipleEmailsField.clear();
    }

    /**
     * Gets the count of generated reviews.
     *
     * @return {jQuery}
     */
    function getReviewsCount() {
        return $(ui.$reviewDetails).length;
    }

    function showGenerateReviewBtn() {
        $(state.config.$trigger).removeClass('d-none');
    }

    function hideGenerateReviewBtn() {
        $(state.config.$trigger).addClass('d-none');
    }

    function showManagementReviewBtn() {
        $(ui.$btnOpenSendReviewByEmailConfirmation).removeClass('d-none');

    }

    function hideManagementReviewBtn() {
        $(ui.$btnOpenSendReviewByEmailConfirmation).addClass('d-none');
    }

    this.init = init;
    this.getReviewHtml = getReviewHtml;
    this.setReviewNumberLabel = setReviewNumberLabel;
    this.showGenerateReviewBtn = showGenerateReviewBtn;
    this.hideGenerateReviewBtn = hideGenerateReviewBtn;
    this.showManagmentReviewBtn = showManagementReviewBtn;
    this.hideManagmentReviewBtn = hideManagementReviewBtn;
}