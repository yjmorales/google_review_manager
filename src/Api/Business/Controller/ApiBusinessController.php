<?php
/**
 * @author Yenier Jimenez <yjmorales86@gmail.com>
 */

namespace App\Api\Business\Controller;

use App\Api\Business\Model\BusinessListModel;
use App\Api\Business\Model\BusinessRemoveModel;
use App\Api\Core\Exception\ApiErrorException;
use App\Api\Core\Exception\ApiNormalOperationException;
use App\Api\Core\Exception\ApiNotFoundException;
use App\Controller\Business\TBusinessController;
use App\Core\Controller\BaseController;
use App\Core\Models\ApiEmptyResponse;
use App\Entity\Business;
use App\Entity\Review;
use App\Repository\Business\BusinessCriteria;
use ArrayObject;
use Common\Communication\HtmlMailer\MailerMessage;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Common\Communication\HtmlMailer\Mailer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * API Controller to manages a business.
 *
 * @Route("/api/business")
 */
class ApiBusinessController extends BaseController
{
    use TBusinessController;

    /**
     * List of business.
     *
     * @throws ApiErrorException
     */
    public function list(ManagerRegistry $doctrine, Request $request): Response
    {
        try {
            $businesses = $this->findBusiness($request, $this->em($doctrine), new BusinessCriteria());
        } catch (Exception $e) {
            throw new ApiErrorException(["Unable to retrieve the business list"], 0, $e);
        }

        return $this->buildJsonResponse(new BusinessListModel($businesses));
    }

    /**
     * Removes business entity.
     *
     * @throws ApiErrorException
     */
    public function remove(ManagerRegistry $doctrine, Business $business): JsonResponse
    {
        try {
            foreach ($business->getReviews() as $review) {
                if ($filename = $review->getQrCodeImgFilename()) {
                    if (file_exists($filename)) {
                        unlink($filename);
                    }
                }
            }
            $removed = clone $business;
            $this->em($doctrine)->remove($business);
            $this->em($doctrine)->flush();
        } catch (Exception $e) {
            throw new ApiErrorException(["Unable to remove the business {$business->getName()}"], 0, $e);
        }

        return $this->buildJsonResponse(new BusinessRemoveModel($removed));
    }

    /**
     * Represents an endpoint used to send a review by email.
     *
     * @ParamConverter("business", class="App\Entity\Business", options={"id" = "business_id"})
     *
     * @param Request         $request  Holds all the data needed to send the review.
     * @param Mailer          $mailer   Holds the mailing logic.
     * @param ManagerRegistry $doctrine Holds doctrine instance to update retrieve the revire info.
     * @param Business        $business Review to be sent via email
     *
     * @return JsonResponse
     * @throws ApiErrorException
     * @throws ApiNormalOperationException
     * @throws ApiNotFoundException
     */
    public function sendByEmail(
        Request $request,
        Mailer $mailer,
        ManagerRegistry $doctrine,
        Business $business
    ): JsonResponse {

        $reviews        = json_decode($request->get('reviewIds'));
        $otherReceivers = json_decode($request->get('otherReceivers'));

        $valid = (new ApiBusinessValidator())->validateEmailSending($reviews, $errors = new ArrayObject());
        if (!$valid) {
            throw new ApiNormalOperationException($errors->getArrayCopy());
        }

        $reviewId = $reviews[0];
        $review   = $this->repository($doctrine, Review::class)->find($reviewId);
        if (!$review) {
            throw new ApiNotFoundException(["The review $reviewId is not found"]);
        }

        $to = [];
        if ($businessEmail = $business->getEmail()) {
            $to[] = $businessEmail;
        }
        if ($otherReceivers) {
            foreach ($otherReceivers as $email) {
                $to[] = $email;
            }
        }

        try {
            $qrCodeBaseName = str_replace($this->_getQrCodeDir(false), '', $review->getQrCodeImgFilename());
            $subject        = "Google Review Link - {$business->getName()}";
            $context        = [
                'review'         => $review,
                'qrCodeBaseName' => $qrCodeBaseName,
            ];
            $msg            = new MailerMessage($subject, ...$to);
            $msg->setContext($context);
            $msg->setHtmlTemplate('/email/business/send_review_by_email.html.twig');
            $sent = $mailer->send($msg);
            if (!$sent) {
                throw new Exception('not sent');
            }
        } catch (Exception $e) {
            throw new ApiErrorException(["Unable to send the email holding the Google Review Link(s). {$e->getMessage()}"]);
        }

        return $this->buildJsonResponse(new ApiEmptyResponse());
    }
}