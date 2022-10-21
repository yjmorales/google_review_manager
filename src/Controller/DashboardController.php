<?php
/**
 * @author Yenier Jimenez <yjmorales86@gmail.com>
 */

namespace App\Controller;

use App\Controller\Core\BaseController;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class acting as dashboard controller.
 */
class DashboardController extends BaseController
{
    /**
     * Main route.
     *
     * @Route("/", name="dashboard")
     */
    public function index(ManagerRegistry $doctrine): Response
    {
        return $this->redirectToRoute('business_list');
    }
}
