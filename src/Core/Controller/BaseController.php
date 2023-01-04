<?php
/**
 * @author Yenier Jimenez <yjmorales86@gmail.com>
 */

namespace App\Core\Controller;

use App\Api\Core\Model\AbstractApiResponseModel;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Persistence\ObjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class acting as a controller abstraction for this application.
 */
abstract class BaseController extends AbstractController
{
    /**
     * Returns the corresponding repository
     *
     * @param ManagerRegistry $doctrine
     * @param string          $className Class name of the requested repository.
     * @param string|null     $em        Entity Manager name.
     *
     * @return ObjectRepository
     */
    protected function _repository(ManagerRegistry $doctrine, string $className, string $em = null): ObjectRepository
    {
        return $this->_em($doctrine, $em)->getRepository($className);
    }

    /**
     * Returns an Entity Manager
     *
     * @param ManagerRegistry $doctrine
     * @param string|null     $em
     *
     * @return ObjectManager
     */
    protected function _em(ManagerRegistry $doctrine, string $em = null): ObjectManager
    {
        return $doctrine->getManager($em);
    }

    /**
     * Use this function to notify a success user event.
     *
     * @param string|null $message Message to be added within the notification.
     *
     * @return void
     */
    protected function _notifySuccess(string $message = null): void
    {
        $this->addFlash('success', $message ?? 'Action performed successfully.');
    }

    /**
     * Use this function to notify an error user event.
     *
     * @param string|null $message
     *
     * @return void
     */
    protected function _notifyError(string $message = null): void
    {
        $this->addFlash('error', $message ?? 'An error occur.');
    }

    /**
     * Use this function to notify an error user event.
     *
     * @param string|null $message
     *
     * @return void
     */
    protected function _notifyWarning(string $message = null): void
    {
        $this->addFlash('warning', $message ?? 'You should check the performed action.');
    }

    /**
     * Returns the absolute project directory.
     *
     * @return string
     */
    protected function _getProjectDir(): string
    {
        return $this->getParameter('kernel.project_dir');
    }

    /**
     * Returns the absolute public directory.
     *
     * @return string
     */
    protected function _getPublicDir(): string
    {
        return "{$this->_getProjectDir()}/public";
    }

    /**
     * Helper function to build the dir where are saved the qr images.
     *
     * @param bool $excludePublicRoot Indicator of whether is used the `public` directory on the path or not.
     *
     * @return string
     */
    protected function _getQrCodeDir(bool $excludePublicRoot = true): string
    {
        $dir = 'google_reviews/qr_codes';
        if (!$excludePublicRoot) {
            $dir = "{$this->_getPublicDir()}/$dir";
        }

        return $dir;
    }

    /**
     * @param AbstractApiResponseModel $apiResponseModel Model holding the information to render.
     * @param int                      $code             Response http status code.
     *
     * @return JsonResponse
     */
    protected function _buildJsonResponse(
        AbstractApiResponseModel $apiResponseModel,
        int $code = Response::HTTP_OK
    ): JsonResponse {
        $response = new JsonResponse($apiResponseModel->toObject(), $code);
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->add(['Pragma' => 'no-cache', 'Expires' => '0']);

        return $response;
    }

    /**
     * Use this function to download a Google review link qr code image.
     *
     * @param string $filename     QR code image absolute path.
     * @param string $fileBaseName QR code image base filename
     * @param string $extention    The file extention.
     *
     * @return BinaryFileResponse
     */
    protected function _downloadFile(string $filename, string $fileBaseName, string $extention): BinaryFileResponse
    {
        return $this->file($filename, "$fileBaseName.$extention");
    }


}