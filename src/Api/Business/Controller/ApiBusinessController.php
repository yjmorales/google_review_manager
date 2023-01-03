<?php
/**
 * @author Yenier Jimenez <yjmorales86@gmail.com>
 */

namespace App\Api\Business\Controller;

use App\Api\Business\Model\BusinessRemoveModel;
use App\Api\Business\Validator\ApiBusinessValidator;
use App\Api\Core\Exception\ApiErrorException;
use App\Api\Core\Exception\ApiNormalOperationException;
use App\Controller\Business\TBusinessController;
use App\Core\Controller\BaseController;
use App\Core\Models\ApiEmptyResponse;
use App\Entity\Business;
use App\Entity\Review;
use ArrayObject;
use Common\Communication\HtmlMailer\MailerMessage;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Common\Communication\HtmlMailer\Mailer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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
            $this->_em($doctrine)->remove($business);
            $this->_em($doctrine)->flush();
        } catch (Exception $e) {
            throw new ApiErrorException(["Unable to remove the business {$business->getName()}"], 0, $e);
        }

        return $this->_buildJsonResponse(new BusinessRemoveModel($removed));
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
     * @throws ApiNormalOperationException
     */
    public function sendByEmail(
        Request $request,
        Mailer $mailer,
        ManagerRegistry $doctrine,
        Business $business
    ): JsonResponse {
        $reviews        = json_decode($request->get('reviewIds'));
        $otherReceivers = json_decode($request->get('otherReceivers'));
        $sendToBusiness = json_decode($request->get('sendToBusiness'));

        $valid = (new ApiBusinessValidator())->validateEmailSending($reviews, $errors = new ArrayObject());
        if (!$valid) {
            throw new ApiNormalOperationException($errors->getArrayCopy());
        }
        $sentEmails = 0;
        foreach ($reviews as $reviewId) {
            $review = $this->_repository($doctrine, Review::class)->find($reviewId);
            if (!$review) {
                $errors->append("The review $reviewId was not found.");
                continue;
            }
            $to = [];
            if ($sendToBusiness && $businessEmail = $business->getEmail()) {
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
                    'downloadLink'   => $this->generateUrl('review_download', ['review_id' => $review->getId()]),
                ];
                $msg            = new MailerMessage($subject, ...$to);
                $msg->setContext($context);
                $msg->setHtmlTemplate('/email/business/send_review_by_email.html.twig');
                $sent = $mailer->send($msg);
                if (!$sent) {
                    throw new Exception('not sent');
                }
                $sentEmails++;
            } catch (Exception $e) {
                $errors->append("Unable to send the Google Review Link $reviewId by email");
            }
        }
        $totalReviews = count($reviews);
        if ($totalReviews && $totalReviews !== $sentEmails) {
            throw new ApiNormalOperationException($errors->getArrayCopy());
        }

        return $this->_buildJsonResponse(new ApiEmptyResponse());
    }
}