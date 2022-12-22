<?php
/**
 * @author Yenier Jimenez <yjmorales86@gmail.com>
 */

namespace App\Controller\Landing;

use App\Core\Controller\BaseController;

use Symfony\Component\Routing\Annotation\Route;

class LandingController extends BaseController
{
    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        return $this->render('/landing/index/index.html.twig', [
            'sys_admin_email'                => $this->getParameter('sys_admin_email'),
            'sys_admin_linkedin_profile_url' => $this->getParameter('sys_admin_linkedin_profile_url'),
            'sys_admin_personal_page_url'    => $this->getParameter('sys_admin_personal_page_url'),
            'sys_admin_phone'                => $this->getParameter('sys_admin_phone'),
        ]);
    }
}