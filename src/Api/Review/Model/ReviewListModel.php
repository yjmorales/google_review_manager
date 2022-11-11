<?php
/**
 * @author Yenier Jimenez <yjmorales86@gmail.com>
 */

namespace App\Api\Business\Model;

use App\Api\Core\Services\QrCodeManager;
use App\Api\Core\Services\QrCodeManager\Exception\QrCodeImgNotFountException;
use App\Core\Models\AbstractApiResponseModel;
use App\Entity\Review;
use Exception;
use stdClass;
use Symfony\Component\Routing\RouterInterface;

/**
 * Class holding the review list respective a business. The information will be returned via json response
 */
class ReviewListModel extends AbstractApiResponseModel
{
    /**
     * Holds the review entity.
     *
     * @var Review[]
     */
    private array $_reviews;

    /**
     * Used to generate routes respective to the given entity.
     *
     * @var RouterInterface
     */
    private RouterInterface $_router;

    /**
     * @param array           $reviews List of businesses.
     * @param RouterInterface $router  Used to generate routes respective to the given entity.
     */
    public function __construct(array $reviews, RouterInterface $router)
    {
        $this->_reviews = $reviews;
        $this->_router  = $router;
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function toObject(): array
    {
        $data = [];
        foreach ($this->_reviews as $review) {
            $item                    = new stdClass();
            $item->id                = $review->getId();
            $item->name              = $review->getName();
            $item->link              = $review->getLink();
            $item->qrCodeImgFilename = $review->getQrCodeImgFilename();
            try {
                $item->qrCodeImgBase64 = QrCodeManager::getQrCodeBase64($review->getQrCodeImgFilename());
            } catch (QrCodeImgNotFountException $e) {
                // Intentionally blank.
            }
            $item->urlRemoveReview = $this->_router->generate('api_v1_review_id_remove_post', [
                'id' => $review->getId()
            ]);
            $item->urlUpdateReview = $this->_router->generate('api_v1_review_id_update_post', [
                'id' => $review->getId()
            ]);

            $data[] = $item;
        }

        return ['reviews' => $data];
    }
}