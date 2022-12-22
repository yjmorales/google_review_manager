<?php
/**
 * @author Yenier Jimenez <yjmorales86@gmail.com>
 */

namespace App\Google\GoogleMap\Place\Services\PlaceDetails;

use App\Google\GoogleMap\Place\Core\GoogleMapPlaceApiServices\AbstractPlaceService;
use App\Google\GoogleMap\Place\Core\GoogleMapPlaceApiServices\IPlaceResponse;
use App\Google\GoogleMap\Place\Core\Transport\Response;
use App\Google\GoogleMap\Place\Core\Transport\ResponseStatus;
use App\Google\GoogleMap\Place\Core\Url\PlaceApiUrls;
use Exception;
use Illuminate\Support\Arr;
use InvalidArgumentException;

/**
 * Service responsible to manage the Google place details service.
 */
class PlaceDetailsService extends AbstractPlaceService
{
    /**
     * Holds the place id used to search the details.
     *
     * @var string|null
     */
    private ?string $placeId;

    /**
     * Uses the Google Place details api and look up for results.
     *
     * @param string $placeId
     *
     * @return IPlaceResponse
     * @throws Exception
     */
    public function details(string $placeId): IPlaceResponse
    {
        $this->placeId = $placeId;
        $fields        = PlaceFields::ADDRESS_COMPONENT;

        return $this->_execute("fields=$fields&{$this->getBaseUrlArguments($placeId)}");
    }

    /**
     * Responsible to load and return the full details of a Place.
     *
     * @param string $placeId Place id
     *
     * @return IPlaceResponse
     * @throws Exception
     */
    public function fullDetails(string $placeId): IPlaceResponse
    {
        $this->placeId = $placeId;
        $fields        = implode(',', [
            PlaceFields::ADDRESS_COMPONENT,
            PlaceFields::NAME,
            PlaceFields::PLACE_ID,
        ]);

        return $this->_execute("fields=$fields&{$this->getBaseUrlArguments($placeId)}");
    }

    /**
     * Builds the base URL used to query the Google Place API.
     *
     * @param string $placeId Place id.
     *
     * @return string
     */
    private function getBaseUrlArguments(string $placeId): string
    {
        return "place_id=$placeId&key=$this->_apiKey";
    }

    /**
     * Extracts from the response the address component section type name.
     *
     * @param Response $dataResponse Response holding the address info.
     * @param string   $type         Represents the address component type name used to filter the data.
     *
     * @return string|null
     */
    private function _getAddressComponentSection(Response $dataResponse, string $type): ?string
    {
        $data              = $dataResponse->getData();
        $addressComponents = Arr::get($data, 'result.address_components', []);
        if (!in_array($type, AddressComponentSectionsType::ADDRESS_TYPES)) {
            return new InvalidArgumentException("The address component type $type is invalid");
        }
        foreach ($addressComponents as $component) {
            $types = $component['types'];
            if (!in_array($type, $types)) {
                continue;
            }

            return (string)$component['short_name'] ?? null;
        }

        return null;
    }

    /**
     * @inheritDoc
     */
    protected function baseUrl(): string
    {
        return PlaceApiUrls::PLACE_DETAILS;
    }

    /**
     * @inheritDoc
     */
    protected function parseResponse(Response $dataResponse): IPlaceResponse
    {
        $data     = $dataResponse->getData();
        $response = new DetailsResponse();
        $response->setSuccess($dataResponse->getStatus() === ResponseStatus::OK);

        // Retrieving Place address component.
        $addressComponents = Arr::get($data, 'result.address_components', []);
        $address           = new Address();
        foreach ($addressComponents as $component) {
            $types = $component['types'];
            foreach ($types as $type) {
                $value = $this->_getAddressComponentSection($dataResponse, $type);
                if (AddressComponentSectionsType::isStreetNumber($type)) {
                    $address->setStreetNumber($value);
                } elseif (AddressComponentSectionsType::isStreetName($type)) {
                    $address->setStreetName($value);
                } elseif (AddressComponentSectionsType::isCity($type)) {
                    $address->setCity($value);
                } elseif (AddressComponentSectionsType::isState($type)) {
                    $address->setState($value);
                } elseif (AddressComponentSectionsType::isCountry($type)) {
                    $address->setCountry($value);
                } elseif (AddressComponentSectionsType::isZipCode($type)) {
                    $address->setZipCode($value);
                }
            }
        }
        $response->setAddress($address);

        // Retrieving place name.
        if ($placeName = Arr::get($data, 'result.name')) {
            $response->setPlaceName($placeName);
        }
        // Retrieving place id.
        if ($this->placeId) {
            $response->setPlaceId($this->placeId);
        }

        return $response;
    }
}