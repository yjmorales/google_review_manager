<?php

namespace App\Repository\Business;

use App\Entity\Business;
use App\Model\ActiveEnum;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Business>
 *
 * @method Business|null find($id, $lockMode = null, $lockVersion = null)
 * @method Business|null findOneBy(array $criteria, array $orderBy = null)
 * @method Business[]    findAll()
 * @method Business[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BusinessRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Business::class);
    }

    public function add(Business $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Business $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Returns an array of Business objects
     *
     * @return Business[]
     */
    public function filter(BusinessCriteria $criteria): array
    {
        $qb         = $this->createQueryBuilder('c');
        $conditions = [];


        if (!empty($name = $criteria->getBusinessName())) {
            $name         = trim($name);
            $conditions[] = ' c.name LIKE :name';
            $qb->setParameter('name', "%$name%");
        }
        if (!empty($createdDate = $criteria->getBusinessCreatedDate())) {
            // todo:
        }
        if (!empty($industrySector = $criteria->getBusinessIndustrySector())) {
            $conditions[] = 'c.industrySector = :industrySector';
            $qb->setParameter('industrySector', "%$industrySector%");
        }
        if (!empty($businessStatus = $criteria->getBusinessStatus())) {
            if ($businessStatus !== ActiveEnum::ALL()->getId()) {
                $conditions[] = 'c.active = :active';
                $qb->setParameter('active', $businessStatus);
            }
        }
        if (!empty($address = $criteria->getBusinessAddress())) {
            $address        = trim($address);
            $conditions[] = 'c.address = :address';
            $qb->setParameter('address', $address);
        }
        if (!empty($state = $criteria->getBusinessState())) {
            $state        = trim($state);
            $conditions[] = 'c.state = :state';
            $qb->setParameter('state', $state);
        }
        if (!empty($city = $criteria->getBusinessCity())) {
            $city         = trim($city);
            $conditions[] = 'c.city = :city';
            $qb->setParameter('city', $city);
        }
        if (!empty($zipCode = $criteria->getBusinessZipCode())) {
            $zipCode      = trim($zipCode);
            $conditions[] = 'c.zipCode = :zipCode';
            $qb->setParameter('zipCode', $zipCode);
        }

        if (count($conditions)) {
            $qb->andWhere(join(' AND ', $conditions));
        }

        return $qb
            ->orderBy('c.name', 'ASC')
            ->getQuery()
            ->getResult();
    }
}