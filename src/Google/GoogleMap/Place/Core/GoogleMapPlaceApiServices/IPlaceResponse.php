<?php
/**
 * @author Yenier Jimenez <yjmorales86@gmail.com>
 */

namespace App\Google\GoogleMap\Place\Core\GoogleMapPlaceApiServices;

/**
 * Each Google map place api service has a different response structure. This defines the actions all responses
 * must implement to be considered a response that hold data returned by Google Map Place API Service Responses.
 */
interface IPlaceResponse
{
    /**
     * All responses must hold the data returned by Google Map Place API Service Responses. This method make it
     * accessible.
     */
    public function getData();
}