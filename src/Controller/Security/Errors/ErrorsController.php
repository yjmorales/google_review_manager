<?php
/**
 * @author Yenier Jimenez <yjmorales86@gmail.com>
 */

namespace App\Controller\Security\Errors;

use App\Core\Controller\BaseController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Responsible display the errors pages.
 */
class ErrorsController extends BaseController
{
    /**
     * @Route("/not-found", name="landing_404_error")
     */
    public function landing404Error(): Response
    {
        return $this->render('/errors/landing/landing_404.html.twig', $this->_argsErrorPageArgs());
    }

    /**
     * @Route("/error", name="landing_500_error")
     */
    public function landing500Error(): Response
    {
        return $this->render('/errors/landing/landing_500.html.twig', $this->_argsErrorPageArgs());
    }

    /**
     * @Route("/admin/not-found", name="admin_404_error")
     */
    public function admin404Error(): Response
    {
        return $this->render('/errors/admin/admin_404.html.twig', $this->_argsErrorPageArgs());
    }

    /**
     * @Route("/admin/error", name="admin_500_error")
     */
    public function admin500Error(): Response
    {
        return $this->render('/errors/admin/admin_500.html.twig', $this->_argsErrorPageArgs());
    }

    /**
     * @return array
     */
    private function _argsErrorPageArgs(): array
    {
        return [
            'sys_admin_email'                => $this->getParameter('sys_admin_email'),
            'sys_admin_linkedin_profile_url' => $this->getParameter('sys_admin_linkedin_profile_url'),
            'sys_admin_personal_page_url'    => $this->getParameter('sys_admin_personal_page_url'),
            'sys_admin_phone'                => $this->getParameter('sys_admin_phone'),
            'owner_company_name'             => $this->getParameter('owner_company_name'),
        ];
    }
}