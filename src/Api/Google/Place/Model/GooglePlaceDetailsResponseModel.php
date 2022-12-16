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
     * @var Address|null
     */
    private ?Address $address = null;

    /**
     * Holds the place name.
     *
     * @var string|null
     */
    private ?string $name;

    /**
     * Google Place Id
     * @var string|null
     */
    private ?string $placeId;

    /**
     * @param stdClass $data
     */
    public function __construct(stdClass $data)
    {
        $this->address = $data->address ?? null;
        $this->name    = $data->name ?? null;
        $this->placeId = $data->placeId ?? null;
    }

    /**
     * @inheritDoc
     */
    public function toObject(): stdClass
    {
        $result          = new stdClass();
        $result->address = $this->address;
        $result->name    = $this->name;
        $result->placeId = $this->placeId;

        return $result;
    }
}