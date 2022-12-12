<?php
/**
 * @author Yenier Jimenez <yjmorales86@gmail.com>
 */

namespace App\Api\Google\Place\Model;

use App\Core\Models\AbstractApiResponseModel;
    use App\Google\GoogleMap\Place\Services\PlaceDetails\Address;
use stdClass;

/**
 * Model holding the place details to be returned via json response to the caller.
 */
class GooglePlaceDetailsResponseModel extends AbstractApiResponseModel
{
    /**
     * Holds the place details.
     *
     * @var Address
     */
    private Address $address;

    /**
     * @param Address $address
     */
    public function __construct(Address $address)
    {
        $this->address = $address;
    }


    /**
     * @inheritDoc
     */
    public function toObject(): stdClass
    {
        $result          = new stdClass();
        $result->address = $this->address;

        return $result;
    }
}