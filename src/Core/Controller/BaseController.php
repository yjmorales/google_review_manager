<?php
/**
 * @author Yenier Jimenez <yjmorales86@gmail.com>
 */

namespace App\Core\Controller;

use App\Core\Models\AbstractApiResponseModel;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Persistence\ObjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

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
    protected function repository(ManagerRegistry $doctrine, string $className, string $em = null): ObjectRepository
    {
        return $this->em($doctrine, $em)->getRepository($className);
    }

    /**
     * Returns an Entity Manager
     *
     * @param ManagerRegistry $doctrine
     * @param string|null     $em
     *
     * @return ObjectManager
     */
    protected function em(ManagerRegistry $doctrine, string $em = null): ObjectManager
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
    protected function notifySuccess(string $message = null): void
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
    protected function notifyError(string $message = null): void
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
    protected function notifyWarning(string $message = null): void
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
     * @param AbstractApiResponseModel $apiResponseModel
     *
     * @return JsonResponse
     */
    protected function buildJsonResponse(AbstractApiResponseModel $apiResponseModel): JsonResponse
    {
        $response = new JsonResponse($apiResponseModel->toObject());
        $response->headers->addCacheControlDirective('no-store');
        $response->headers->addCacheControlDirective('no-cache');
        $response->headers->addCacheControlDirective('must-revalidate');
        $response->headers->add(['Pragma' => 'no-cache', 'Expires' => '0']);

        return $response;
    }
}