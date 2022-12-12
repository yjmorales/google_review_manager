<?php
/**
 * @author Yenier Jimenez <yjmorales86@gmail.com>
 */

namespace App\Google\GoogleMap\Place\Services\PlaceDetails;

use App\Google\GoogleMap\Place\Core\GoogleMapPlaceApiServices\AbstractPlaceResponse;

/**
 * Response holding the Place Details information.
 */
class DetailsResponse extends AbstractPlaceResponse
{
    private Address $address;

    /**
     * @param Address $address
     */
    public function setAddress(Address $address): void
    {
        $this->address = $address;
    }

    /**
     * @inheritDoc
     */
    public function getData()
    {
        return $this->address;
    }

    /**
     * @return Address
     */
    public function getAddress(): Address
    {
        return $this->address;
    }
}