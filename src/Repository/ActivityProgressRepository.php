<?php

namespace App\Repository;

use App\Entity\ActivityProgress;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ActivityProgress|null find($id, $lockMode = null, $lockVersion = null)
 * @method ActivityProgress|null findOneBy(array $criteria, array $orderBy = null)
 * @method ActivityProgress[]    findAll()
 * @method ActivityProgress[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ActivityProgressRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ActivityProgress::class);
    }

    // /**
    //  * @return ActivityProgress[] Returns an array of ActivityProgress objects
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
    public function findOneBySomeField($value): ?ActivityProgress
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
