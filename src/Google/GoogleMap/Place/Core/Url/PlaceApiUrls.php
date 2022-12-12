<?php
/**
 * @author Yenier Jimenez <yjmorales86@gmail.com>
 */

namespace App\Google\GoogleMap\Place\Core\Url;
/**
 * Holds the Google Map API Base urls per service.
 */
class PlaceApiUrls
{
    /**
     * Place Autocomplete service.
     *
     * @link: https://developers.google.com/maps/documentation/places/web-service/autocomplete
     */
    const PLACE_AUTOCOMPLETE = 'https://maps.googleapis.com/maps/api/place/autocomplete';

    /**
     * Place details service.
     *
     * @link: https://developers.google.com/maps/documentation/places/web-service/details
     */
    const PLACE_DETAILS = 'https://maps.googleapis.com/maps/api/place/details';
}