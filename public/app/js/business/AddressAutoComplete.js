'use strict';

/**
 * Component responsible to find address matches and display them as options. Once the user selects an option
 * the rest of address properties will be populated with the details.
 */
function AddressAutoComplete() {

    const state = {
        modules: {
            InputSpinner: new InputSpinner(),
        }
    };
    /**
     * UI Elements.
     */
    const ui = {
        $fieldAddress: $('#business_form_address'),
        $fieldCity: $('#business_form_city'),
        $fieldState: $('#business_form_state'),
        $fieldZipCode: $('#business_form_zipCode'),
        $fieldPlaceId: $('#business_form_place'),
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
                    jQuery.get(url, {
                        term: request.term
                    }, function (data) {
                        state.modules.InputSpinner.stop(ui.$fieldAddress);
                        response(data.predictions);
                    });
                },
                select: (event, ui) => {
                    $('#business_form_address').val(ui.item.label);
                    findDetails(ui.item.value, $('#business_form_place').val());
                    return false;
                }
            });
    }

    /**
     *  Once the user selects an option the rest of address properties will be populated with the details.
     * @param {string} placeId Holds the place id respective to the selected option.
     * @param {int} businessId Holds the business id.
     */
    function findDetails(placeId, businessId) {
        if (!placeId) {
            throw 'The place id is required to find a place details.';
        }
        const url = ui.$urls.data('api_v1_google_place_details_post')
            , data = new FormData();
        ui.$fieldPlaceId.val(placeId);
        data.append('placeId', placeId);
        data.append('businessId', businessId);
        state.modules.InputSpinner.start(ui.$fieldAddress);
        fetch(url, {method: 'POST', body: data})
            .then((response) => response.json())
            .then((data) => {
                const addressObj = data.address;
                ui.$fieldAddress.val(addressObj.address);
                ui.$fieldCity.val(addressObj.city);
                ui.$fieldState.val(addressObj.state);
                ui.$fieldZipCode.val(addressObj.zipCode);
            })
            .catch((e) => {
                console.debug(e)
            })
            .finally(() => {
                state.modules.InputSpinner.stop(ui.$fieldAddress);
            });
    }

    /**
     * Function used to initialize the module.
     * @return {void}
     */
    function init() {
        populateAddressOptions();
    }

    this.init = init;
}