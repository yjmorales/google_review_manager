<?php
/**
 * @author Yenier Jimenez <yjmorales86@gmail.com>
 */

namespace App\Api\Business\Controller;

use App\Api\Business\Model\BusinessListModel;
use App\Api\Business\Model\BusinessRemoveModel;
use App\Api\Core\Exception\ApiErrorException;
use App\Controller\Business\TBusinessController;
use App\Core\Controller\BaseController;
use App\Entity\Business;
use App\Repository\Business\BusinessCriteria;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
}