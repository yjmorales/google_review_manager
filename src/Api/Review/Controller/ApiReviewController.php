<?php
/**
 * @author Yenier Jimenez <yjmorales86@gmail.com>
 */

namespace App\Api\Review\Controller;

use App\Api\Review\Model\ReviewListModel;
use App\Api\Review\Model\ReviewModel;
use App\Api\Core\Controller\TApiController;
use App\Api\Core\Exception\ApiErrorException;
use App\Api\Core\Exception\ApiNormalOperationException;
use App\Api\Core\Services\GoogleReviewManager\ApiGoogleReviewManager;
use App\Api\Core\Services\QrCodeManager;
use App\Api\Google\Place\Model\PlaceMutator;
use App\Core\Controller\BaseController;
use App\Entity\Business;
use App\Entity\Place;
use App\Entity\Review;
use App\Google\GoogleMap\Place\Services\PlaceDetails\Address;
use App\Entity\IndustrySector;
use App\Google\GoogleMap\Place\Services\PlaceDetails\PlaceBusinessStatusTypes;
use App\Google\GoogleMap\Place\Services\PlaceDetails\PlaceDetailsService;
use Common\Communication\HtmlMailer\MailerMessage;
use Common\DataManagement\Validator\DataValidator;
use Common\Security\AntiSpam\ReCaptcha\v3\ReCaptchaV3Validator;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Common\Communication\HtmlMailer\Mailer;

/**
 * API Controller to manage review
 */
class ApiReviewController extends BaseController
{
    use TApiController;

    /**
     * Gets all business reviews entities.
     *
     * @ParamConverter("business", class="App\Entity\Business", options={"id" = "business_id"})
     * @param Business        $business
     * @param RouterInterface $router
     *
     * @return Response
     * @throws Exception
     */
    public function all(Business $business, RouterInterface $router): Response
    {
        return $this->_buildJsonResponse(new ReviewListModel($business, $router));
    }

    /**
     * Generates a new review.
     *
     * @ParamConverter("business", class="App\Entity\Business", options={"id" = "business_id"})
     * @param Request                $request             Holds the place id used to generate the review link.
     * @param ManagerRegistry        $doctrine            Responsible to save the review entity in db.
     * @param Business               $business            Holds the business owning the new review.
     * @param RouterInterface        $router              Used to generate routes to be returned to the action caller.
     * @param ApiGoogleReviewManager $googleReviewManager Used to generate the Google Review link.
     *
     * @return Response
     * @throws ApiErrorException
     * @throws Exception
     */
    public function generate(
        Request $request,
        ManagerRegistry $doctrine,
        Business $business,
        RouterInterface $router,
        ApiGoogleReviewManager $googleReviewManager,
        LoggerInterface $logger
    ): Response {
        if (!$placeId = $request->get('place_id')) {
            throw new ApiErrorException(['The place id is required']);
        }
        //  Generating the Google Review link respective to the business,
        try {
            $link = $googleReviewManager->generateLink($placeId);
        } catch (Exception $e) {
            throw new ApiErrorException(['Unable to generate the Google Review link'], 0, $e);
        }
        // Storing the review entity.
        try {
            // Saving the entity into the db
            $review = new Review();
            $review->setName($business->getName());
            $review->setLink($link);
            $review->setBusiness($business);
            $this->_createQrCode($review);
            $this->_em($doctrine)->persist($review);
            $place = $this->_repository($doctrine, Place::class)->findOneByPlaceId($placeId);
            if ($place) {
                $business->setPlace($place);
                $this->_em($doctrine)->persist($business);
            }
            $this->_em($doctrine)->flush();
        } catch (Exception $e) {
            $logger->error($e->getMessage());
            throw new ApiErrorException([$e->getMessage()], 0, $e);
        }

        return $this->_buildJsonResponse(new ReviewModel($review, $router));
    }

    /**
     * Generates a new review on the landing page.
     *
     * @param Request                $request             Holds the place id used to generate the review link.
     * @param ManagerRegistry        $doctrine            Responsible to save the review entity in db.
     * @param RouterInterface        $router              Used to generate routes to be returned to the action caller.
     * @param ApiGoogleReviewManager $googleReviewManager Used to generate the Google Review link.
     * @param PlaceDetailsService    $detailsService      Responsible to look up the place details in Google Place API.
     * @param Mailer                 $mailer              Responsible to send the email.
     *
     * @return Response
     * @throws ApiErrorException
     * @throws ApiNormalOperationException
     */
    public function generateGuest(
        Request $request,
        ManagerRegistry $doctrine,
        RouterInterface $router,
        ApiGoogleReviewManager $googleReviewManager,
        PlaceDetailsService $detailsService,
        Mailer $mailer,
        ReCaptchaV3Validator $reCaptchaV3Validator,
        LoggerInterface $logger
    ): Response {

        /*
        *  Verifies that's not a robot.
        */
        $this->_validateReCaptchaV3($reCaptchaV3Validator, $logger);

        // All normal. Continue...
        // The place if is mandatory to look up the place info and generate the review.
        if (!$placeId = $request->get('place_id')) {
            throw new ApiErrorException(['The place id is required']);
        }
        if (!$email = $request->get('email')) {
            throw new ApiErrorException(['The email is requited']);
        }

        $validator = new DataValidator();
        $valid     = true;
        $valid     &= $validator->isValidString($placeId);
        $valid     &= $validator->isValidString($email);
        if (!$valid) {
            throw new ApiNormalOperationException(['The input data is invalid.']);
        }

        // Search first in DB. If it's not in DB then find it in Google and save it in DB for future queries.
        /** @var Place $place */
        $place = $this->_repository($doctrine, Place::class)->findOneByPlaceId($placeId);
        if (!$place) {
            $placeDetails = $detailsService->fullDetails($placeId);
            /** @var Address $address */
            $address = $placeDetails->getData()->address;
            if ($address && $address->getStreetNumber()) {
                $place = PlaceMutator::fromAddress($address);
            }
            if ($placeDetails->getData()->name) {
                $place->setName($placeDetails->getData()->name);
            }
            if ($placeDetails->getData()->placeId) {
                $place->setPlaceId($placeDetails->getData()->placeId);
            }
            $place->setActive(false);
            if ($status = $placeDetails->getData()->businessStatus) {
                $place->setActive(PlaceBusinessStatusTypes::OPERATIONAL === $status);
            }
            if ($type = $placeDetails->getData()->placeType) {
                $place->setType($type);
            }
            $this->_em($doctrine)->persist($place);
        }

        // Find Business in DB. If it is not saved yet then save it into DB.
        /** @var Business $business */
        $business = $this->_repository($doctrine, Business::class)->findOneBy(['place' => $place,]);
        if (!$business) {
            $business = new Business();
            $business->setName($place->getName());
            $business->setActive($place->isActive());
            $business->setAddress("{$place->getStreetNumber()} {$place->getStreetName()}");
            $business->setCity($place->getCity());
            $business->setState($place->getState());
            $business->setZipCode($place->getZipCode());
            $business->setPlace($place);
            /** @var IndustrySector $industry */
            $industry = $this->_repository($doctrine, IndustrySector::class)->findOneBy([
                'name' => $place->getType()
            ]);
            if ($industry) {
                $business->setIndustrySector($industry);
            }
            $this->_em($doctrine)->persist($business);
        }

        //  Generating the Google Review link respective to the business,
        try {
            $link = $googleReviewManager->generateLink($placeId);
        } catch (Exception $e) {
            throw new ApiErrorException(['Unable to generate the Google Review link'], 0, $e);
        }
        // Storing the review entity.
        try {
            $review = $this->_repository($doctrine, Review::class)->findOneByBusiness($business);
            if (!$review) {
                // Saving the entity into the db
                $review = new Review();
                $review->setName($business->getName());
                $review->setLink($link);
                $review->setBusiness($business);
                $this->_createQrCode($review);
                $this->_em($doctrine)->persist($review);
            }
        } catch (Exception $e) {
            $logger->error($e->getMessage());
            throw new ApiErrorException([$e->getMessage()], 0, $e);
        }

        // Saving in DB everything
        $this->_em($doctrine)->flush();

        // Sending review by email.
        try {
            $qrCodeBaseName = str_replace($this->_getQrCodeDir(false), '', $review->getQrCodeImgFilename());
            $subject        = "Google Review Link - {$business->getName()}";
            $context        = [
                'review'          => $review,
                'qrCodeBaseName'  => $qrCodeBaseName,
                'downloadLink'    => $this->generateUrl('review_download', ['review_id' => $review->getId()]),
                'businessName'    => $business->getName(),
                'businessAddress' => $place->getFullAddress(),
            ];
            $msg            = new MailerMessage($subject, $email);
            $msg->setContext($context);
            $msg->setHtmlTemplate('/email/business/send_review_by_email.html.twig');
            $sent = $mailer->send($msg);
            if (!$sent) {
                throw new Exception('not sent');
            }
        } catch (Exception $e) {
            $logger->error($e->getMessage());
            throw new ApiErrorException(["Unable to send the Google Review Link {$review->getId()} by email"]);
        }

        return $this->_buildJsonResponse(new ReviewModel($review, $router));
    }

    /**
     * Removes a given review.
     *
     * @param ManagerRegistry $doctrine
     * @param Review          $review
     * @param RouterInterface $router
     *
     * @return Response
     */
    public function remove(ManagerRegistry $doctrine, Review $review, RouterInterface $router): Response
    {
        $copy = clone $review;
        $this->_handleRemove($doctrine, $review);

        return $this->_buildJsonResponse(new ReviewModel($copy, $router));
    }

    /**
     * Updates the given review entity.
     *
     * @param ManagerRegistry $doctrine
     * @param Request         $request
     * @param Review          $review
     * @param RouterInterface $router
     *
     * @return Response
     * @throws ApiErrorException
     */
    public function update(
        ManagerRegistry $doctrine,
        Request $request,
        Review $review,
        RouterInterface $router
    ): Response {
        try {
            $link = $request->get('link');
            $review->setLink($link);
            $this->_createQrCode($review);
            $this->_em($doctrine)->persist($review);
            $this->_em($doctrine)->flush();
        } catch (Exception $e) {
            throw new ApiErrorException([$e->getMessage()], 0, $e);
        }

        return $this->_buildJsonResponse(new ReviewModel($review, $router));
    }

    /**
     * Helper function used to create the qr code respective to the Google Review link.
     *
     * @param Review $review Holds the link.
     *
     * @return void
     * @throws Exception
     */
    private function _createQrCode(Review $review): void
    {
        if (!$link = $review->getLink()) {
            throw new Exception('To generate a QR Code a `link` property needs to be set.');
        }

        // Generating QR code and save it into the dir
        $qrCodeBase64 = (new QrCodeManager())->generate($link);
        $dir          = $this->_initQrCodeDir();
        $fileName     = "$dir/" . md5(uniqid('google_review'));
        file_put_contents($fileName, file_get_contents($qrCodeBase64));
        $review->setQrCodeImgFilename($fileName);
        $review->setQrCodeBase64($qrCodeBase64);
    }

    /**
     * Helper function to initialize the dir where qr code will be saved.
     *
     * @return string
     * @throws ApiErrorException Thrown if any error emerged.
     */
    private function _initQrCodeDir(): string
    {
        $dir = $this->_getQrCodeDir(false);
        if (!file_exists($dir)) {
            if (!mkdir($dir, 0777, true)) {
                throw new ApiErrorException(['Unable to create dir for QR codes']);
            }
        }

        return $dir;
    }

    /**
     * Handles the common operations to remove a review entity.
     *
     * @param ManagerRegistry $doctrine
     * @param Review          $review
     *
     * @return void
     */
    private function _handleRemove(ManagerRegistry $doctrine, Review $review): void
    {
        $filename = $review->getQrCodeImgFilename();
        $this->_em($doctrine)->remove($review);
        $this->_em($doctrine)->flush();
        if ($filename) {
            if (file_exists($filename)) {
                unlink($filename);
            }
        }
    }
}