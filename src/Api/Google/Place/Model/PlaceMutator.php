<?php
/**
 * @author Yenier Jimenez <yjmorales86@gmail.com>
 */

namespace App\Api\Google\Place\Model;

use App\Entity\Place;
use App\Google\GoogleMap\Place\Services\PlaceDetails\Address;

/**
 * Class used to mutate the information between Address and Place.
 */
class PlaceMutator
{
    /**
     * Mutates a Place from an Address.
     *
     * @param Address    $address Holds the data source.
     * @param Place|null $place   If now provided then returns a new instance.
     *
     * @return Place
     */
    public static function fromAddress(Address $address, Place $place = null): Place
    {
        if (!$place) {
            $place = new Place();
        }
        if ($address->getStreetNumber()) {
            $place->setStreetNumber($address->getStreetNumber());
        }
        if ($address->getStreetName()) {
            $place->setStreetName($address->getStreetName());
        }
        if ($address->getCity()) {
            $place->setCity($address->getCity());
        }
        if ($address->getState()) {
            $place->setState($address->getState());
        }
        if ($address->getZipCode()) {
            $place->setZipCode($address->getZipCode());
        }
        if ($address->getCountry()) {
            $place->setCountry($address->getCountry());
        }
        if ($address->getPlaceId()) {
            $place->setPlaceId($address->getPlaceId());
        }

        return $place;
    }

    /**
     * Mutates an Address from Place.
     *
     * @param Place $place Holds the data source.
     *
     * @return Address
     */
    public static function addressFromPlace(Place $place): Address
    {
        $address = new Address();
        if ($place->getStreetNumber()) {
            $address->setStreetNumber($place->getStreetNumber());
        }
        if ($place->getStreetName()) {
            $address->setStreetName($place->getStreetName());
        }
        if ($place->getCity()) {
            $address->setCity($place->getCity());
        }
        if ($place->getState()) {
            $address->setState($place->getState());
        }
        if ($place->getZipCode()) {
            $address->setZipCode($place->getZipCode());
        }
        if ($place->getCountry()) {
            $address->setCountry($place->getCountry());
        }
        if ($place->getId()) {
            $address->setPlaceId($place->getId());
        }

        return $address;
    }
}