<?php

namespace App\Api\Google\Place\Model;

use App\Entity\Place;
use App\Google\GoogleMap\Place\Services\PlaceDetails\Address;

class PlaceMutator
{
    public static function fromAddress(Address $address): Place
    {
        $place = new Place();
        $place->setStreetNumber($address->getStreetNumber());
        $place->setStreetName($address->getStreetName());
        $place->setCity($address->getCity());
        $place->setState($address->getState());
        $place->setZipCode($address->getZipCode());
        $place->setCountry($address->getCountry());

        return $place;
    }

    public static function addressFromPlace(Place $place): Address
    {
        $address = new Address();
        $address->setStreetNumber($place->getStreetNumber());;
        $address->setStreetName($place->getStreetName());;
        $address->setCity($place->getCity());;
        $address->setState($place->getState());;
        $address->setZipCode($place->getZipCode());;
        $address->setCountry($place->getCountry());;

        return $address;
    }

}