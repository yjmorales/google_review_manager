<?php
/**
 * @author Yenier Jimenez <yjmorales86@gmail.com>
 */

namespace App\Controller;

use App\Entity\BusinessModel;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class acting as dashboard controller.
 */
class DashboardController extends AbstractController
{
    /**
     * Main route.
     *
     * @Route("/", name="dashboard")
     */
    public function index(): Response
    {
        $model = new BusinessModel();

        return $this->render('dashboard/dashboard.html.twig', [
            'business' => $model->generate(),
        ]);
    }
}
