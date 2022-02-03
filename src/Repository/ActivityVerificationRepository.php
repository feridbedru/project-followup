<?php

namespace App\Repository;

use App\Entity\ActivityVerification;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ActivityVerification|null find($id, $lockMode = null, $lockVersion = null)
 * @method ActivityVerification|null findOneBy(array $criteria, array $orderBy = null)
 * @method ActivityVerification[]    findAll()
 * @method ActivityVerification[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ActivityVerificationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ActivityVerification::class);
    }

    // /**
    //  * @return ActivityVerification[] Returns an array of ActivityVerification objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ActivityVerification
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
