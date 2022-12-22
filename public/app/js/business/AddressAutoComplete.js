'use strict';

/**
 * Component responsible to find address matches and display them as options. Once the user selects an option
 * the rest of address properties will be populated with the details.
 */
function AddressAutoComplete(config = {}) {

    /**
     * Holds the auto-complete state.
     * @type {Object}
     */
    const state = {
        modules: {
            InputSpinner: new InputSpinner(),
            FindPlaceDetails: config.FindPlaceDetails ?? new FindPlaceDetails(),
        }
    };
    /**
     * UI Elements.
     */
    const ui = {
        $fieldAddress: $('input[data-id="business_form_address"]'),
        $fieldCity: $('input[data-id="business_form_city"]'),
        $fieldState: $('input[data-id="business_form_state"]'),
        $fieldZipCode: $('input[data-id="business_form_zipCode"]'),
        $fieldPlaceId: $('input[data-id="business_form_place"]'),
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
                            response(data.predictions);
                        })
                        .catch((e) => {
                            console.debug(e)
                        })
                        .finally(() => {
                            state.modules.InputSpinner.stop(ui.$fieldAddress);
                        });
                },
                select: (event, ui) => {
                    $('#business_form_address').val(ui.item.label);
                    if(config.lookForDetails){
                        state.modules.FindPlaceDetails.findDetails(ui.item.value, $('#business_form_place').val());
                    }
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
        ui.$fieldCity.val(null);
        ui.$fieldState.val(null);
        ui.$fieldZipCode.val(null);
        ui.$fieldPlaceId.val(null);
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