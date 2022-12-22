'use strict';

/**
 * Component used to locate places. It performs an ajax call to the API. Then renders the results on UI.
 */
function FindPlaceDetails(config) {

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
        $fieldCity: $('input[data-id="business_form_city"]'),
        $fieldState: $('input[data-id="business_form_state"]'),
        $fieldZipCode: $('input[data-id="business_form_zipCode"]'),
        $fieldPlaceId: $('input[data-id="business_form_place"]'),
        $urls: $('#urls'),
    };

    /**
     *  Once the user selects an option the rest of address properties will be populated with the details.
     * @param {string} placeId Holds the place id respective to the selected option.
     * @param {int} businessId Holds the business id.
     * @return {void}
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
                if (config.callback && 'function' === typeof config.callback) {
                    config.callback(data);
                } else {
                    const addressObj = data.address;
                    ui.$fieldAddress.val(addressObj.address);
                    ui.$fieldCity.val(addressObj.city);
                    ui.$fieldState.val(addressObj.state);
                    ui.$fieldZipCode.val(addressObj.zipCode);
                }
            })
            .catch((e) => {
                console.debug(e)
            })
            .finally(() => {
                state.modules.InputSpinner.stop(ui.$fieldAddress);
            });
    }

    this.findDetails = findDetails;
}