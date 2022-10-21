<?php
/**
 * @author Yenier Jimenez <yjmorales86@gmail.com>
 */

namespace App\Controller\Business;

use App\Controller\Core\BaseController;
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
    /**
     * Main route.
     *
     * @Route("/", name="business_list")
     */
    public function list(ManagerRegistry $doctrine, Request $request): Response
    {
        $industrySectors = $this->em($doctrine)->getRepository(IndustrySector::class)->findAll();
        $criteria        = new BusinessCriteria();
        if ($request->getQueryString()) {
            $criteria->setBusinessName($request->get('businessName'));
            $criteria->setBusinessCreatedDate($request->get('businessCreatedDate'));
            $criteria->setBusinessIndustrySector($request->get('businessIndustrySector'));
            $criteria->setBusinessStatus($request->get('businessStatus'));
            $criteria->setBusinessAddress($request->get('businessAddress'));
            $criteria->setBusinessState($request->get('businessState'));
            $criteria->setBusinessCity($request->get('businessCity'));
            $criteria->setBusinessZipCode($request->get('businessZipCode'));
        }

        $businesses = $this->em($doctrine)->getRepository(Business::class)->filter($criteria);

        return $this->render('/business/business_list.html.twig', [
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
        $response = $this->redirectToRoute('dashboard');

        if (!$this->_save($request, $doctrine, $form, $business)) {
            $response = $this->render('/business/business_create.html.twig', [
                'form'       => $form->createView(),
                'breadcrumb' => [
                    'Dashboard'       => $this->generateUrl('dashboard'),
                    'Create business' => $this->generateUrl('business_create'),
                ],
            ]);
        }

        return $response;
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
            $response = $this->render('/business/business_edit.html.twig', [
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
        $this->em($doctrine)->remove($business);

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
            $action = $isEdit ? 'updated' : 'inserted';

            $this->notifySuccess("The business {$business->getName()} has been $action successfully.");

            return true;
        }

        return false;
    }
}