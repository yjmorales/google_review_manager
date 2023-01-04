'use strict';

/**
 * Manages the landing page components.
 *
 * @constructor
 */
function LandingPage() {
    /**
     * @type {Object}
     */
    const state = {
        modules: {
            LandingPageAddressAutoComplete: null,
            Notification: new Notification(),
            InputSpinner: new InputSpinner(),
        }
    };

    /**
     * UI Elements
     * @type {Object}
     */
    const ui = {
        $smartWizard: $('#smartWizard'),

        // Google Review Link results.
        $googleReviewResults: '#googleReviewResults',
        $yourGoogleReviewLink: '#yourGoogleReviewLink',
        $linkGoogleReviewLink: '#linkGoogleReviewLink',
        $imgGoogleReviewQrCode: '#imgGoogleReviewQrCode',
        $linkToGoogleReviewResults: '#linkToGoogleReviewResults',
        $googleReviewLinkResultAddress: '#googleReviewLinkResultAddress',
        $googleReviewLinkResultBusinessName: '#googleReviewLinkResultBusinessName',

        // buttons
        $btnNext: '#smartWizard .sw-btn-next',
        $btnGenerateGoogleReviewLink: '#btnGenerateGoogleReviewLink',
        $btnCopyToClipBoard: '#btnCopyToClipBoard',

        // Fields
        $fieldPlaceId: $('input[data-id="business_form_place"]'),
        $fieldContactEmail: '#form_contact_email',
        $fieldBusinessAddress: $('input[name="business_form[address]"]'),
    };

    /**
     * Subscribes the GUI events
     */
    function listenUIEvents() {
        $(document).on('click', ui.$btnGenerateGoogleReviewLink, generate);
        $(document).on('click', $('#btnGoToContactPage'), validateBusinessInfo);
    }

    /**
     * Validates the Business fields.
     * Uses jquery validator.
     * @link: https://jqueryvalidation.org/
     */
    function validateBusinessInfo() {
        (new BusinessAddressValidator()).init({callback: goToContactPage});
    }

    /**
     * Initializes the wizard component.
     * @link http://techlaboratory.net/jquery-smartwizard
     */
    function init() {
        // Subscribes GUI events
        listenUIEvents();
        state.modules.LandingPageAddressAutoComplete = new LandingPageAddressAutoComplete();

        // Init Autocomplete modules
        state.modules.LandingPageAddressAutoComplete.init();
        state.modules.LandingPageAddressAutoComplete.clearForm();
        $(ui.$fieldContactEmail).val(null);


        // Init jquery wizard component.
        ui.$smartWizard.smartWizard(
            {
                selected: 0, // Initial selected step, 0 = first step
                theme: 'arrows', // theme for the wizard, related css need to include for other than default theme
                justified: true, // Nav menu justification. true/false
                autoAdjustHeight: false, // Automatically adjust content height
                backButtonSupport: true, // Enable the back button support
                enableUrlHash: true, // Enable selection of the step based on url hash
                transition: {
                    animation: 'none', // Animation effect on navigation, none|fade|slideHorizontal|slideVertical|slideSwing|css(Animation CSS class also need to specify)
                    speed: '400', // Animation speed. Not used if animation is 'css'
                    easing: '', // Animation easing. Not supported without a jQuery easing plugin. Not used if animation is 'css'
                    prefixCss: '', // Only used if animation is 'css'. Animation CSS prefix
                    fwdShowCss: '', // Only used if animation is 'css'. Step show Animation CSS on forward direction
                    fwdHideCss: '', // Only used if animation is 'css'. Step hide Animation CSS on forward direction
                    bckShowCss: '', // Only used if animation is 'css'. Step show Animation CSS on backward direction
                    bckHideCss: '', // Only used if animation is 'css'. Step hide Animation CSS on backward direction
                },
                toolbar: {
                    position: 'none', // none|top|bottom|both
                    showNextButton: false, // show/hide a Next button
                    showPreviousButton: false, // show/hide a Previous button
                    extraHtml: '' // Extra html to show on toolbar
                },
                anchor: {
                    enableNavigation: true, // Enable/Disable anchor navigation
                    enableNavigationAlways: false, // Activates all anchors clickable always
                    enableDoneState: true, // Add done state on visited steps
                    markPreviousStepsAsDone: true, // When a step selected by url hash, all previous steps are marked done
                    unDoneOnBackNavigation: false, // While navigate back, done state will be cleared
                    enableDoneStateNavigation: true // Enable/Disable the done state navigation
                },
                keyboard: {
                    keyNavigation: true, // Enable/Disable keyboard navigation(left and right keys are used if enabled)
                    keyLeft: [37], // Left key code
                    keyRight: [39] // Right key code
                },
                lang: { // Language variables for button
                    next: 'Next',
                    previous: 'Previous'
                },
                disabledSteps: [], // Array Steps disabled
                errorSteps: [], // Array Steps error
                warningSteps: [], // Array Steps warning
                hiddenSteps: [], // Hidden steps
                getContent: null // Callback function for content loading
            }
        );
        ui.$smartWizard.on('loaded', () => {
            $(ui.$btnNext).prop('disabled', true);
            const info = ui.$smartWizard.smartWizard('getStepInfo');
            if (info.currentStep > 0) {
                ui.$smartWizard.smartWizard('reset');
            }
        });
    }

    /**
     * Navigates to 1st wizard page
     * @return {void}
     */
    function goToContactPage() {
        ui.$smartWizard.smartWizard("next");
    }

    /**
     * Holds the logic to generate a review.
     */
    function generate() {
        (new ContactFormValidator()).init(
            {
                callback: () => {

                    // First verifies that the submission is not a robot. Via ReCaptcha v3
                    const $btn = $(ui.$btnGenerateGoogleReviewLink);
                    const siteKey = $btn.data('sitekey');
                    grecaptcha.ready(function () {
                        grecaptcha.execute(siteKey, {action: 'submit'})
                            .then(function (token) {

                                // Once the token is got the following submits the data to generate the review. The token
                                // should be passed.
                                token = token.replace('\n', '');

                                const email = $(ui.$fieldContactEmail).val();
                                if (!Boolean(email)) {
                                    state.modules.Notification.error('An email address is required.');
                                    return;
                                }

                                $btn.prop('disabled', true);
                                state.modules.InputSpinner.start($(ui.$fieldContactEmail));
                                $($btn.data('input')).prop('readonly', true);

                                const body = new FormData();
                                body.append('place_id', ui.$fieldPlaceId.val());
                                body.append('email', email);
                                body.append('g-recaptcha-response', token);

                                const url = $btn.data('url');
                                fetch(url, {method: 'POST', body: body})
                                    .then((response) => response.json())
                                    .then((data) => {
                                        renderGoogleReviewLink(data.review);
                                        state.modules.Notification.success(
                                            `The Google Review Link has been successfully created. 
                                          An email has been sent to those contact email address you provided on the contact form
                                          Thank you for using our Google Review Ling generator.`, 'Google Review Link', 8000);
                                        const timerToResetWizard = setTimeout(function () {
                                            state.modules.LandingPageAddressAutoComplete.clearForm();
                                            $(ui.$fieldContactEmail).val(null);
                                            ui.$smartWizard.smartWizard('reset');
                                            $('a[href="#step-2-email-to-send-results"]').removeClass('active');
                                            clearTimeout(timerToResetWizard);
                                        }, 700);
                                    }).catch((e) => {
                                    console.debug(e)
                                }).finally(() => {
                                    $btn.prop('disabled', false);
                                    $($btn.data('input')).prop('readonly', false);
                                    state.modules.InputSpinner.stop($(ui.$fieldContactEmail));
                                });
                            });
                    });
                }
            });
    }

    /**
     * Once the Google Review Link is generated this will render the component holding it.
     * @param {Object} review
     * @return {void}
     */
    function renderGoogleReviewLink(review) {
        // Adding the data response to the corresponding component.
        $(ui.$linkGoogleReviewLink)
            .text(review.link)
            .attr('href', review.link);
        $(ui.$btnCopyToClipBoard).data('review-link', review.link);
        $(ui.$imgGoogleReviewQrCode).attr('src', review.qrCodeImgBase64);
        $(ui.$googleReviewLinkResultAddress).text(review.fullAddress);
        $(ui.$googleReviewLinkResultBusinessName).text(review.name);

        // Scrolling the page to the generated results
        $(ui.$linkToGoogleReviewResults).removeClass('d-none');
        $(ui.$yourGoogleReviewLink).removeClass('d-none');
        const top = $(ui.$linkToGoogleReviewResults).offset().top + 470;
        $('html, body').animate({scrollTop: top}, 700);
    }

    this.init = init;
}