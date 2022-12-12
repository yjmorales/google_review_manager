<?php
/**
 * @author Yenier Jimenez <yjmorales86@gmail.com>
 */

namespace App\Controller\Business;

use App\Core\Controller\BaseController;
use App\Entity\Business;
use App\Entity\IndustrySector;
use App\Entity\Place;
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
        $industrySectors = $this->_em($doctrine)->getRepository(IndustrySector::class)->findAll();
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
        $businesses = $this->_em($doctrine)->getRepository(Business::class)->filter($criteria);

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
        $form = $this->createForm(BusinessFormType::class, $business);
        if ($business->getPlace() && $placeId = $business->getPlace()->getPlaceId()) {
            $form->get('place')->setData($placeId);
        }
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
            $em = $this->_em($doctrine);
            if ($placeId = $form->get('place')->getData()) {
                $place = $this->_repository($doctrine, Place::class)->findOneByPlaceId($placeId);
                if ($place) {
                    $business->setPlace($place);
                }
            }
            $em->persist($business);
            $em->flush();
            if ($isEdit) {
                $this->_notifySuccess("The business \"{$business->getName()}\" has been updated successfully.");
            } else {
                $this->_notifySuccess("The business \"{$business->getName()}\" has been created successfully. You are able to generate its Google Review Links.");
            }

            return true;
        }

        return false;
    }
}