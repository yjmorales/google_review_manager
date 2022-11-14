<?php
/**
 * @author Yenier Jimenez <yjmorales86@gmail.com>
 */

namespace App\Api\Review\Controller;

use App\Api\Business\Model\ReviewListModel;
use App\Api\Business\Model\ReviewModel;
use App\Api\Core\Exception\ApiErrorException;
use App\Api\Core\Exception\ApiNormalOperationException;
use App\Api\Core\Services\GoogleReviewManager\ApiGoogleReviewManager;
use App\Api\Core\Services\QrCodeManager;
use App\Core\Controller\BaseController;
use App\Core\Models\ApiEmptyResponse;
use App\Entity\Business;
use App\Entity\Review;
use Common\Communication\Mailer\SendGrid\Mailer;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use SendGrid\Mail\To;
use SendGrid\Mail\TypeException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Routing\RouterInterface;

/**
 * API Controller to manage review
 */
class ApiReviewController extends BaseController
{
    /**
     * Gets all the businesses entities.
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
        return $this->buildJsonResponse(new ReviewListModel($business->getReviews()->getValues(), $router));
    }

    /**
     * Generates a new review.
     *
     * @ParamConverter("business", class="App\Entity\Business", options={"id" = "business_id"})
     * @param ManagerRegistry        $doctrine            Responsible to save the review entity in db.
     * @param Business               $business            Holds the business owning the new review.
     * @param RouterInterface        $router              Used to generate routes to be returned to the action caller.
     * @param ApiGoogleReviewManager $googleReviewManager Used to generate the Google Review link.
     *
     * @return Response
     * @throws ApiErrorException
     */
    public function generate(
        ManagerRegistry $doctrine,
        Business $business,
        RouterInterface $router,
        ApiGoogleReviewManager $googleReviewManager
    ): Response {
        //  Generating the Google Review link respective to the business,
        try {
            $link = $googleReviewManager->generateLink($business);
        } catch (Exception $e) {
            throw new ApiErrorException(['Unable to generate the Google Review link'], 0, $e);
        }
        // Storing the review entity.
        try {
            // Saving the entity into the db
            $review = new Review();
            $review->setName('Review');
            $review->setLink($link);
            $review->setBusiness($business);
            $this->_createQrCode($review);
            $this->em($doctrine)->persist($review);
            $this->em($doctrine)->flush();
        } catch (Exception $e) {
            throw new ApiErrorException([$e->getMessage()], 0, $e);
        }



        return $this->buildJsonResponse(new ReviewModel($review, $router));
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

        return $this->buildJsonResponse(new ReviewModel($copy, $router));
    }

    /**
     * Removes multiples reviews.
     *
     * @param Request         $request
     * @param ManagerRegistry $doctrine
     * @param RouterInterface $router
     *
     * @return Response
     */
    public function removeMultiple(
        Request $request,
        ManagerRegistry $doctrine,
        RouterInterface $router
    ): Response {
        $reviewData = $request->get('reviews', '');
        $reviewIds  = json_decode($reviewData, true);
        $repository = $this->repository($doctrine, Review::class);
        $copy       = [];
        foreach ($reviewIds as $id) {
            if (!$review = $repository->find($id)) {
                continue;
            }
            $copy[] = clone $review;
            $this->_handleRemove($doctrine, $review);
        }

        return $this->buildJsonResponse(new ReviewListModel($copy, $router));
    }

    /**
     * Removes all reviews.
     *
     * @ParamConverter("business", class="App\Entity\Business", options={"id" = "business_id"})
     *
     * @param ManagerRegistry $doctrine
     * @param Business        $business
     *
     * @return Response
     * @throws ApiErrorException
     */
    public function removeAll(ManagerRegistry $doctrine, Business $business): Response
    {
        try {
            $reviews = $this->repository($doctrine, Review::class)->findBy(['business' => $business]);
            foreach ($reviews as $review) {
                $this->_handleRemove($doctrine, $review);
            }
        } catch (Exception $e) {
            throw new ApiErrorException(["Unable to remove all reviews from business {$business->getId()}"], 0, $e);
        }

        return $this->buildJsonResponse(new ApiEmptyResponse());
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
            $this->em($doctrine)->persist($review);
            $this->em($doctrine)->flush();
        } catch (Exception $e) {
            throw new ApiErrorException([$e->getMessage()], 0, $e);
        }

        return $this->buildJsonResponse(new ReviewModel($review, $router));
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
//        if (false === file_put_contents($fileName, $qrCodeBase64)) {
//            throw new Exception('Unable to save the QR code on the respective directory');
//        }
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
        $this->em($doctrine)->remove($review);
        $this->em($doctrine)->flush();
        if ($filename) {
            if (file_exists($filename)) {
                unlink($filename);
            }
        }
    }
}