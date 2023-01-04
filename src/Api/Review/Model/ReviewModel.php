<?php
/**
 * @author Yenier Jimenez <yjmorales86@gmail.com>
 */

namespace App\Api\Review\Model;

use App\Api\Core\Model\AbstractApiResponseModel;
use App\Api\Core\Services\QrCodeManager;
use App\Api\Core\Services\QrCodeManager\Exception\QrCodeImgNotFountException;
use App\Entity\Review;
use Exception;
use stdClass;
use Symfony\Component\Routing\RouterInterface;

/**
 * Class holding the business list information to be returned via json response
 */
class ReviewModel extends AbstractApiResponseModel
{
    /**
     * Holds the review entity.
     *
     * @var Review
     */
    private Review $_review;

    /**
     * Used to generate routes respective to the given entity.
     *
     * @var RouterInterface
     */
    private RouterInterface $_router;

    /**
     * @param Review          $review Represents a review.
     * @param RouterInterface $router Used to generate routes respective to the given entity.
     */
    public function __construct(Review $review, RouterInterface $router)
    {
        $this->_review = $review;
        $this->_router = $router;
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function toObject(): stdClass
    {
        $data                    = new stdClass();
        $data->id                = $this->_review->getId();
        $data->name              = $this->_review->getName();
        $data->link              = $this->_review->getLink();
        $data->business          = $this->_review->getBusiness()->getId();
        $data->businessEmail     = $this->_review->getBusiness()->getEmail();
        $data->qrCodeImgFilename = $this->_review->getQrCodeImgFilename();
        $data->fullAddress       = $this->_review->getBusiness()->getPlace()->getFullAddress();;
        try {
            $data->qrCodeImgBase64 = QrCodeManager::getQrCodeBase64($this->_review->getQrCodeImgFilename());
        } catch (QrCodeImgNotFountException $e) {
            // Intentionally blank.
        }
        $data->urlRemoveReview = $this->_router->generate('api_v1_review_id_remove_post',
            ['id' => $this->_review->getId()]);
        $data->urlUpdateReview = $this->_router->generate('api_v1_review_id_update_post',
            ['id' => $this->_review->getId()]);

        $result         = new stdClass();
        $result->review = $data;

        return $result;
    }
}