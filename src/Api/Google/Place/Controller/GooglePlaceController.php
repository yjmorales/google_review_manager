<?php
/**
 * @author Yenier Jimenez <yjmorales86@gmail.com>
 */

namespace App\Api\Google\Place\Controller;

use App\Api\Core\Exception\ApiNormalOperationException;
use App\Api\Google\Place\Model\GooglePlaceAutocompleteResponseModel;
use App\Api\Google\Place\Model\GooglePlaceDetailsResponseModel;
use App\Api\Google\Place\Model\PlaceMutator;
use App\Core\Controller\BaseController;
use App\Entity\Place;
use App\Google\GoogleMap\Place\Services\PlaceAutocomplete\PlaceAutocompleteService;
use App\Google\GoogleMap\Place\Services\PlaceDetails\DetailsResponse;
use App\Google\GoogleMap\Place\Services\PlaceDetails\PlaceBusinessStatusTypes;
use App\Google\GoogleMap\Place\Services\PlaceDetails\PlaceDetailsService;
use Common\DataManagement\Validator\DataValidator;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Responsible to query the Google map place api to retrieve information about places.
 *
 * @Route("/google/place")
 */
class GooglePlaceController extends BaseController
{
    /**
     * Responsible to returns the autocomplete info respective to a place. It uses the Google Map Place API.
     *
     * @link: https://developers.google.com/maps/documentation/places/web-service/autocomplete
     *
     * @Route("/autocomplete", name="google_place_autocomplete")
     *
     * @param Request                  $request                  Holds the user input.
     * @param PlaceAutocompleteService $placeAutocompleteService Holds the logic to retrieve the results.
     *
     * @return JsonResponse
     * @throws ApiNormalOperationException
     * @throws Exception
     */
    public function autocomplete(Request $request, PlaceAutocompleteService $placeAutocompleteService): JsonResponse
    {
        $input = $request->get('term');
        $valid = (new DataValidator())->isValidString($input);
        if (!$valid) {
            throw new ApiNormalOperationException(['The `input` validation failed.']);
        }
        $response = $placeAutocompleteService->autocomplete($input);

        return $this->_buildJsonResponse(new GooglePlaceAutocompleteResponseModel($response->getData()));
    }

    /**
     * Responsible to returns the place details. It uses the Google Map Place API.
     *
     * @link: https://developers.google.com/maps/documentation/places/web-service/details#AddressComponent
     *
     * @Route("/details", name="google_place_details")
     *
     * @param Request             $request             Holds the user input.
     * @param ManagerRegistry     $doctrine            If the place id is found in DB, then it is used instead of hit
     *                                                 the Google API again.
     * @param PlaceDetailsService $placeDetailsService Holds the logic to retrieve the results.
     *
     * @return JsonResponse
     * @throws ApiNormalOperationException
     */
    public function details(
        Request $request,
        ManagerRegistry $doctrine,
        PlaceDetailsService $placeDetailsService
    ): JsonResponse {
        $placeId   = $request->get('placeId');
        $validator = new DataValidator();
        if (!$validator->isValidString($placeId)) {
            throw new ApiNormalOperationException(['The `place id` validation failed.']);
        }
        $place = $this->_repository($doctrine, Place::class)->findOneBy([
            'placeId' => $placeId
        ]);
        /** @var DetailsResponse $response */
        $response = $placeDetailsService->fullDetails($placeId);
        if (!$place) {
            $address = $response->getAddress();
            $place   = PlaceMutator::fromAddress($address);
            $place->setPlaceId($placeId);
            $place->setName($response->getData()->name);
            $place->setActive(PlaceBusinessStatusTypes::OPERATIONAL === $response->getBusinessStatus());
            $place->setType($response->getPlaceType());
            $this->_em($doctrine)->persist($place);;
            $this->_em($doctrine)->flush();
        } else {
            $place = PlaceMutator::fromAddress($response->getAddress(), $place);
            $response->setAddress(PlaceMutator::addressFromPlace($place));
        }

        return $this->_buildJsonResponse(new GooglePlaceDetailsResponseModel($response->getData()));
    }
}