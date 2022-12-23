<?php
/**
 * @author Yenier Jimenez <yjmorales86@gmail.com>
 */

namespace App\Google\GoogleMap\Place\Services\PlaceDetails;

/**
 * Holds the Place Business Status Types used by the Google Place API to retrieve information.
 *
 * @link: https://developers.google.com/maps/documentation/javascript/reference/places-service#BusinessStatus
 */
class PlaceBusinessStatusTypes
{
    const CLOSED_PERMANENTLY = 'CLOSED_PERMANENTLY'; // The business is closed permanently.
    const CLOSED_TEMPORARILY = 'CLOSED_TEMPORARILY'; //	The business is closed temporarily.
    const OPERATIONAL = 'OPERATIONAL'; // The business is operating normally.
}