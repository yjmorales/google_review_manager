<?php
/**
 * @author Yenier Jimenez <yjmorales86@gmail.com>
 */

namespace App\Api\Google\Place\Model;

use App\Core\Models\AbstractApiResponseModel;
use App\Google\GoogleMap\Place\Services\PlaceAutocomplete\Prediction;
use stdClass;

/**
 * Model holding the autocomplete predictions to be returned via json response to the caller.
 */
class GooglePlaceAutocompleteResponseModel extends AbstractApiResponseModel
{
    /**
     * Holds the places predictions.
     *
     * @var Prediction[]
     */
    private array $predictions;

    /**
     * @param Prediction[] $predictions
     */
    public function __construct(array $predictions)
    {
        $this->predictions = $predictions;
    }

    /**
     * @inheritDoc
     */
    public function toObject(): stdClass
    {
        $result              = new stdClass();
        $result->predictions = [];
        foreach ($this->predictions as $prediction) {
            $result->predictions[] = $prediction;
        }

        return $result;
    }
}