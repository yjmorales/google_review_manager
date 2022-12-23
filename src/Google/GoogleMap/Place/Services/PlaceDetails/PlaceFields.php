<?php
/**
 * @author Yenier Jimenez <yjmorales86@gmail.com>
 */

namespace App\Google\GoogleMap\Place\Services\PlaceDetails;

/**
 * Holds the fields used by the Google Place API to retrieve information.
 * @link: https://developers.google.com/maps/documentation/places/web-service/place-data-fields
 */
class PlaceFields
{
    const ADDRESS_COMPONENT = 'address_component';
    const BUSINESS_STATUS = 'business_status';
    const NAME = 'name';
    const PLACE_ID = 'place_id';
    const FORMATTED_PHONE_NUMBER = 'formatted_phone_number';
    const TYPE = 'type';
}