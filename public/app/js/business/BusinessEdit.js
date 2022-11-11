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
            DotsLoaderManager: new DotsLoaderManager(),
            GoogleReviewLinkManager: new GoogleReviewLinkManager(),
            BusinessFormValidator: new BusinessFormValidator(),
        }, clicked: {
            btnRemoveReview: null, urlRemoveReview: null, btnUpdateReview: null, urlUpdateReview: null,
        }
    }
    /**
     * UI Elements.
     */
    const ui = {
        $urls: $('#urls'),
        // Buttons
        $btnGenerateReviewOnEditForm: '#btnGenerateReviewOnEditForm',
        // Modals & Containers
        $googleReviewListContainer: '#googleReviewListContainer',
        $reviewListLoader: $('#review-list-loader'),
        $form: $('form[name="business_form"]'),
    };

    /**
     * Finds and renders all the Google reviews for the given business.
     * @return {void}
     */
    function loadReviewsByBusiness() {
        const url = ui.$urls.data('api_v1_business_business_id_review_all_get');
        fetch(url)
            .then((result) => result.json())
            .then((data) => {
                if (!data.reviews.length) {
                    state.modules.GoogleReviewLinkManager.setReviewNumberLabel();
                    return;
                }
                data.reviews.forEach((review) => {
                    addReviewToDom(state.modules.GoogleReviewLinkManager.getReviewHtml(review));
                });
            })
            .catch((e) => {
                state.modules.Notification.warning(`The business information was loaded successfully, 
                however this page was unable to load its Google Review Links.', 'Google Review Links`);
            })
            .finally(() => {
                ui.$reviewListLoader.addClass('d-none');
            });
    }

    /**
     * This functions adds a review component to the DOM
     * @param {string} reviewHtml represents the review html.
     * @return {void}
     */
    function addReviewToDom(reviewHtml) {
        $(ui.$googleReviewListContainer).prepend(reviewHtml);
    }

    /**
     * This function is a proxy between the form validation module and the browser. It triggers the form submission
     * whenever the validation is success.
     */
    function submit(form) {
        state.modules.LoaderManager.startOverlay();
        form.submit();
    }

    /**
     * Function used to initialize the module.
     * @return {void}
     */
    function init() {
        const reviewGeneratorConfig = {
            $trigger: ui.$btnGenerateReviewOnEditForm,
            $loading: state.modules.DotsLoaderManager,
            $reviewContainer: ui.$googleReviewListContainer,
        };
        state.modules.GoogleReviewLinkManager.init(reviewGeneratorConfig);
        const validationConfig = {callback: submit,};
        state.modules.BusinessFormValidator.init(validationConfig);
    }

    this.init = init;
    this.loadReviewsByBusiness = loadReviewsByBusiness;
}