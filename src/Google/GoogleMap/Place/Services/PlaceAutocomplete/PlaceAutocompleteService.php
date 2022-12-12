<?php
/**
 * @author Yenier Jimenez <yjmorales86@gmail.com>
 */

namespace App\Google\GoogleMap\Place\Services\PlaceAutocomplete;

use App\Google\GoogleMap\Place\Core\GoogleMapPlaceApiServices\AbstractPlaceService;
use App\Google\GoogleMap\Place\Core\GoogleMapPlaceApiServices\IPlaceResponse;
use App\Google\GoogleMap\Place\Core\Transport\Response;
use App\Google\GoogleMap\Place\Core\Transport\ResponseStatus;
use App\Google\GoogleMap\Place\Core\Url\PlaceApiUrls;
use Exception;
use Illuminate\Support\Arr;
use InvalidArgumentException;

/**
 * Service responsible to manage the Google place autocomplete service.
 */
class PlaceAutocompleteService extends AbstractPlaceService
{
    /**
     * Uses the Google Place Autocomplete api and look up for results.
     *
     * @param string $input Input to be queried.
     *
     * @throws Exception
     */
    public function autocomplete(string $input): IPlaceResponse
    {
        $input = urlencode($input);
        $key   = $this->_apiKey;

        return $this->_execute("input=$input&key=$key");
    }

    /**
     * @inheritDoc
     */
    protected function baseUrl(): string
    {
        return PlaceApiUrls::PLACE_AUTOCOMPLETE;
    }

    /**
     * @inheritDoc
     */
    protected function parseResponse(Response $dataResponse): IPlaceResponse
    {
        $data        = $dataResponse->getData();
        $status      = Arr::get($data, 'status');
        $predictions = Arr::get($data, 'predictions');
        if (empty($status)) {
            throw new InvalidArgumentException("The `status` key is not present on the response data.");
        }
        $autocompleteResponse = new AutocompleteResponse();
        $autocompleteResponse->setSuccess(ResponseStatus::OK === $status);
        foreach ($predictions as $prediction) {
            $placeId = Arr::get($prediction, 'place_id');
            $label   = Arr::get($prediction, 'description');
            if (!$placeId) {
                throw new InvalidArgumentException("The `place_id` key is not present on the response data.");
            }
            if (!$label) {
                throw new InvalidArgumentException("The `description` key is not present on the response data.");
            }
            $prediction = new Prediction($placeId, $label);
            $autocompleteResponse->addPrediction($prediction);
        }

        return $autocompleteResponse;
    }
}