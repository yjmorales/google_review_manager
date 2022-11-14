<?php
/**
 * @author Yenier Jimenez <yjmorales86@gmail.com>
 */

namespace App\Controller\Business;

use App\Core\Controller\BaseController;
use App\Entity\Business;
use App\Entity\IndustrySector;
use App\Form\BusinessFormType;
use App\Model\ActiveEnum;
use App\Repository\Business\BusinessCriteria;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Responsible to manage the business.
 *
 * @Route("/business")
 */
class BusinessController extends BaseController
{
    use TBusinessController;

    /**
     * Main route.
     *
     * @Route("/", name="business_list")
     */
    public function list(ManagerRegistry $doctrine, Request $request): Response
    {
        $industrySectors = $this->em($doctrine)->getRepository(IndustrySector::class)->findAll();
        $criteria        = new BusinessCriteria();
        $businesses      = $this->findBusiness($request, $this->em($doctrine), $criteria);

        return $this->render('/business/list/business_list.html.twig', [
            'businesses'     => $businesses,
            'activeEnum'     => ActiveEnum::map(),
            'industrySector' => $industrySectors,
            'criteria'       => $criteria,
            'filtered'       => (bool)$request->getQueryString(),
        ]);
    }

    /**
     * Creates a new business entity.
     *
     * @Route("/create", name="business_create")
     */
    public function create(Request $request, ManagerRegistry $doctrine): Response
    {
        $business = new Business();
        $form     = $this->createForm(BusinessFormType::class, $business);

        if (!$this->_save($request, $doctrine, $form, $business)) {
            return $this->render('/business/create_edit/business_create.html.twig', [
                'business'   => $business,
                'form'       => $form->createView(),
                'breadcrumb' => [
                    'Dashboard'       => $this->generateUrl('dashboard'),
                    'Create business' => $this->generateUrl('business_create'),
                ],
            ]);
        }

        return $this->redirectToRoute('business_edit', ['id' => $business->getId()]);
    }

    /**
     * Edits a new business entity.
     *
     * @Route("/{id}/edit", name="business_edit")
     */
    public function edit(Request $request, ManagerRegistry $doctrine, Business $business): Response
    {
        $form     = $this->createForm(BusinessFormType::class, $business);
        $response = $this->redirectToRoute('dashboard');
        if (!$this->_save($request, $doctrine, $form, $business)) {
            $response = $this->render('/business/create_edit/business_edit.html.twig', [
                'form'       => $form->createView(),
                'business'   => $business,
                'breadcrumb' => [
                    'Dashboard'     => $this->generateUrl('dashboard'),
                    'Edit business' => $this->generateUrl('business_edit', ['id' => $business->getId()]),
                ],
            ]);
        }

        return $response;
    }

    /**
     * Removes business entity.
     *
     * @Route("/{id}/remove", name="business_remove")
     */
    public function remove(ManagerRegistry $doctrine, Business $business): Response
    {
        foreach ($business->getReviews() as $review) {
            if ($filename = $review->getQrCodeImgFilename()) {
                if (file_exists($filename)) {
                    unlink($filename);
                }
            }
        }
        $this->em($doctrine)->remove($business);
        $this->notifySuccess("The business \"{$business->getName()}\" has been removed successfully.");

        return $this->render('/dashboard/dashboard.html.twig');
    }

    /**
     * Handles common actions used to save a business.
     *
     * @param Request         $request
     * @param ManagerRegistry $doctrine
     * @param FormInterface   $form
     * @param Business        $business
     *
     * @return bool
     */
    private function _save(Request $request, ManagerRegistry $doctrine, FormInterface $form, Business $business): bool
    {
        $isEdit = (bool)$business->getId();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->em($doctrine);
            $em->persist($business);
            $em->flush();
            if ($isEdit) {
                $this->notifySuccess("The business \"{$business->getName()}\" has been updated successfully.");
            } else {
                $this->notifySuccess("The business \"{$business->getName()}\" has been created successfully. You are able to generate its Google Review Links.");
            }

            return true;
        }

        return false;
    }
}