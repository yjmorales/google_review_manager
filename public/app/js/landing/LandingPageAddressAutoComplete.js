'use strict';

/**
 * Component responsible to find address matches and display them as options.
 */
function LandingPageAddressAutoComplete() {

    /**
     * Holds the auto-complete state.
     * @type {Object}
     */
    const state = {
        modules: {
            InputSpinner: new InputSpinner(),
        }
    };
    /**
     * UI Elements.
     */
    const ui = {
        $fieldAddress: $('input[data-id="business_form_address"]'),
        $fieldPlaceId: $('input[data-id="business_form_place"]'),
        $btnGoToContactPage: $('#btnGoToContactPage'),
        $urls: $('#urls'),
    };

    /**
     * Once the user updates the address field this look for the respective places options by making an ajax call to
     * and endpoint which in turn looks for the result on Google.
     * @return {void}
     */
    function populateAddressOptions() {
        const url = ui.$urls.data('api_v1_google_place_autocomplete_get');
        ui.$fieldAddress.autocomplete(
            {
                source: function (request, response) {
                    state.modules.InputSpinner.start(ui.$fieldAddress);
                    fetch(`${url}?term=${request.term}`)
                        .then((response) => response.json())
                        .then((data) => {
                            if(data.status > 299){
                                throw '';
                            }
                            response(data.predictions);
                        })
                        .catch((e) => {
                            (new Notification()).error('Unable to find the location. Please try later.', 'An error occurs.')

                        })
                        .finally(() => {
                            state.modules.InputSpinner.stop(ui.$fieldAddress);
                        });
                },
                select: (event, ui) => {
                    $('#business_form_address').val(ui.item.label);
                    $('input[data-id="business_form_place"]').val(ui.item.value);
                    $('#btnGoToContactPage').prop('disabled', false);
                    return false;
                }
            });
    }

    /**
     * Used to clear all business form fields.
     * @return {void}
     */
    function clearForm() {
        ui.$fieldAddress.val(null);
        ui.$fieldPlaceId.val(null);
        ui.$btnGoToContactPage.prop('disabled', true);
    }

    /**
     * Function used to initialize the module.
     * @return {void}
     */
    function init() {
        populateAddressOptions();
    }

    this.init = init;
    this.clearForm = clearForm;
}