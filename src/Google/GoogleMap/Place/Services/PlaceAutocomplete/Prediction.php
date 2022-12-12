<?php
/**
 * @author Yenier Jimenez <yjmorales86@gmail.com>
 */

namespace App\Google\GoogleMap\Place\Services\PlaceAutocomplete;

use JsonSerializable;

/**
 * Maps the prediction data returned by the Google Place Autocomplete endpoint.
 *
 * @link: https://developers.google.com/maps/documentation/places/web-service/autocomplete#PlacesAutocompleteResponse
 */
class Prediction implements JsonSerializable
{
    /**
     * @var string
     */
    private string $_placeId;

    /**
     * @var string
     */
    private string $_label;

    /**
     * @param string $placeId
     * @param string $label
     */
    public function __construct(string $placeId, string $label)
    {
        $this->_placeId = $placeId;
        $this->_label   = $label;
    }

    /**
     * @return string
     */
    public function getPlaceId(): string
    {
        return $this->_placeId;
    }

    /**
     * @param string $placeId
     */
    public function setPlaceId(string $placeId): void
    {
        $this->_placeId = $placeId;
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->_label;
    }

    /**
     * @param string $label
     */
    public function setLabel(string $label): void
    {
        $this->_label = $label;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return [
            'value' => $this->_placeId,
            'label'   => $this->_label,
        ];
    }
}