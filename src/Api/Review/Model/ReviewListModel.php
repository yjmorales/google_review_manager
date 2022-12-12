<?php
/**
 * @author Yenier Jimenez <yjmorales86@gmail.com>
 */

namespace App\Api\Business\Model;

use App\Api\Core\Services\QrCodeManager;
use App\Api\Core\Services\QrCodeManager\Exception\QrCodeImgNotFountException;
use App\Core\Models\AbstractApiResponseModel;
use App\Entity\Business;
use Exception;
use stdClass;
use Symfony\Component\Routing\RouterInterface;

/**
 * Class holding the review list respective a business. The information will be returned via json response
 */
class ReviewListModel extends AbstractApiResponseModel
{
    /**
     * Business holding the reviews to be returned as response data.
     *
     * @var Business
     */
    private Business $business;

    /**
     * Used to generate routes respective to the given entity.
     *
     * @var RouterInterface
     */
    private RouterInterface $_router;


    /**
     * @param Business        $business Business holding the reviews to be returned as response data.
     * @param RouterInterface $router   Used to generate routes respective to the given entity.
     */
    public function __construct(Business $business, RouterInterface $router)
    {
        $this->business = $business;
        $this->_router  = $router;
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function toObject(): stdClass
    {
        $data = [];
        foreach ($this->business->getReviews() as $review) {
            $item                    = new stdClass();
            $item->id                = $review->getId();
            $item->name              = $review->getName();
            $item->link              = $review->getLink();
            $item->qrCodeImgFilename = $review->getQrCodeImgFilename();
            $item->businessEmail     = $this->business->getEmail();
            try {
                $item->qrCodeImgBase64 = QrCodeManager::getQrCodeBase64($review->getQrCodeImgFilename());
            } catch (QrCodeImgNotFountException $e) {
                // Intentionally blank.
            }
            $item->urlRemoveReview   = $this->_router->generate('api_v1_review_id_remove_post',
                ['id' => $review->getId()]);
            $item->urlUpdateReview   = $this->_router->generate('api_v1_review_id_update_post',
                ['id' => $review->getId()]);
            $item->urlDownloadReview = $this->_router->generate('review_download', ['review_id' => $review->getId()]);

            $data[] = $item;
        }
        $result          = new stdClass();
        $result->reviews = $data;

        return $result;
    }
}