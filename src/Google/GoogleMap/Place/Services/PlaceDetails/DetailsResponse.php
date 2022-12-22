<?php
/**
 * @author Yenier Jimenez <yjmorales86@gmail.com>
 */

namespace App\Google\GoogleMap\Place\Services\PlaceDetails;

use App\Google\GoogleMap\Place\Core\GoogleMapPlaceApiServices\AbstractPlaceResponse;
use stdClass;

/**
 * Response holding the Place Details information.
 */
class DetailsResponse extends AbstractPlaceResponse
{
    private ?Address $address = null;

    private ?string $placeName = null;

    private ?string $placeId = null;

    /**
     * @param Address $address
     */
    public function setAddress(Address $address): void
    {
        $this->address = $address;
    }

    /**
     * @param string $placeName
     */
    public function setPlaceName(string $placeName): void
    {
        $this->placeName = $placeName;
    }

    /**
     * @param string $placeId
     *
     * @return void
     */
    public function setPlaceId(string $placeId)
    {
        $this->placeId = $placeId;
    }

    /**
     * @inheritDoc
     */
    public function getData()
    {
        $data          = new stdClass();
        $data->address = $this->address;
        $data->name    = $this->placeName;
        $data->placeId = $this->placeId;

        return $data;
    }

    /**
     * @return Address|null
     */
    public function getAddress(): ?Address
    {
        return $this->address;
    }
}