<?php
/**
 * @author Yenier Jimenez <yjmorales86@gmail.com>
 */

namespace App\Controller\Business;

use App\Entity\Business;
use App\Repository\Business\BusinessCriteria;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;

/**
 * Trait holding the common functions to manage a business from the controllers.
 */
trait TBusinessController
{
    /**
     * Function used to filter the list of business.
     *
     * @param Request          $request
     * @param ObjectManager    $em
     * @param BusinessCriteria $criteria
     *
     * @return Business[]
     */
    public function findBusiness(Request $request, ObjectManager $em, BusinessCriteria $criteria): array
    {
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

        return $em->getRepository(Business::class)->filter($criteria);
    }
}