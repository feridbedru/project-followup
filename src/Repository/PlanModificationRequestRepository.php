<?php

namespace App\Repository;

use App\Entity\PlanModificationRequest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PlanModificationRequest|null find($id, $lockMode = null, $lockVersion = null)
 * @method PlanModificationRequest|null findOneBy(array $criteria, array $orderBy = null)
 * @method PlanModificationRequest[]    findAll()
 * @method PlanModificationRequest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlanModificationRequestRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PlanModificationRequest::class);
    }

    // /**
    //  * @return PlanModificationRequest[] Returns an array of PlanModificationRequest objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PlanModificationRequest
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
