<?php
/**
 * @author Yenier Jimenez <yjmorales86@gmail.com>
 */

namespace App\Google\GoogleMap\Place\Services\PlaceAutocomplete;


use App\Google\GoogleMap\Place\Core\GoogleMapPlaceApiServices\AbstractPlaceResponse;

/**
 * Response holding the Place Autocomplete information.
 */
class AutocompleteResponse extends AbstractPlaceResponse
{
    /**
     * List of predictions returned by the endpoint.
     *
     * @var Prediction[]
     */
    private array $_predictions = [];


    /**
     * Adds a new prediction.
     *
     * @param Prediction $prediction
     *
     * @return void
     */
    public function addPrediction(Prediction $prediction): void
    {
        $exists = array_filter($this->_predictions, function (Prediction $item) use ($prediction) {
            return $prediction->getPlaceId() === $item->getPlaceId();
        });
        if (count($exists)) {
            return;
        }
        $this->_predictions[] = $prediction;
    }

    /**
     * @inheritDoc
     */
    public function getData()
    {
        return $this->_predictions;
    }
}