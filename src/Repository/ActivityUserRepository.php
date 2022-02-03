<?php

namespace App\Repository;

use App\Entity\ActivityUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ActivityUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method ActivityUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method ActivityUser[]    findAll()
 * @method ActivityUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ActivityUserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ActivityUser::class);
    }

    // /**
    //  * @return ActivityUser[] Returns an array of ActivityUser objects
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
    public function findOneBySomeField($value): ?ActivityUser
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
