<?php

namespace App\Repository;

use App\Entity\IndustrySector;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<IndustrySector>
 *
 * @method IndustrySector|null find($id, $lockMode = null, $lockVersion = null)
 * @method IndustrySector|null findOneBy(array $criteria, array $orderBy = null)
 * @method IndustrySector[]    findAll()
 * @method IndustrySector[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IndustrySectorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, IndustrySector::class);
    }

    public function add(IndustrySector $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(IndustrySector $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}