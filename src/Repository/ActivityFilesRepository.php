<?php

namespace App\Repository;

use App\Entity\ActivityFiles;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ActivityFiles|null find($id, $lockMode = null, $lockVersion = null)
 * @method ActivityFiles|null findOneBy(array $criteria, array $orderBy = null)
 * @method ActivityFiles[]    findAll()
 * @method ActivityFiles[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ActivityFilesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ActivityFiles::class);
    }

    // /**
    //  * @return ActivityFiles[] Returns an array of ActivityFiles objects
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
    public function findOneBySomeField($value): ?ActivityFiles
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
