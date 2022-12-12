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
     * Uses the Google Place details api and look up for results.
     *
     * @param string $placeId
     *
     * @return IPlaceResponse
     * @throws Exception
     */
    public function details(string $placeId): IPlaceResponse
    {
        return $this->_execute("fields=address_component&place_id=$placeId&key=$this->_apiKey");
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
        $addressComponents = Arr::get($data, 'result.address_components');
        $address = new Address();
        foreach ($addressComponents as $component) {
            $types = $component['types'];
            foreach ($types as $type) {
                $value = $this->_getAddressComponentSection($addressComponents, $type);
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

        return $response;
    }

    /**
     * @param array  $addressComponents
     * @param string $type
     *
     * @return string|null
     */
    private function _getAddressComponentSection(array $addressComponents, string $type): ?string
    {
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
}