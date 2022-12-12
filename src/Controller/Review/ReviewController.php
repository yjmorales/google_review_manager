<?php
/**
 * @author Yenier Jimenez <yjmorales86@gmail.com>
 */

namespace App\Controller\Review;

use App\Core\Controller\BaseController;
use App\Entity\Review;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * Responsible to manage reviews.
 *
 * @Route("/review")
 */
class ReviewController extends BaseController
{
    /**
     * Download a Google review link qr code img.
     *
     * @Route("/{review_id}/download", name="review_download")
     * @ParamConverter("review", class="App\Entity\Review", options={"id" = "review_id"})
     *
     * @param Review $review
     *
     * @return BinaryFileResponse
     */
    public function download(Review $review): BinaryFileResponse
    {
        $basename = uniqid('google-review-link-qr-code-');

        return $this->_downloadFile($review->getQrCodeImgFilename(), $basename, 'png');
    }
}