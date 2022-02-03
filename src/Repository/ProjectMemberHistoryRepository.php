<?php

namespace App\Repository;

use App\Entity\ProjectMemberHistory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ProjectMemberHistory|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProjectMemberHistory|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProjectMemberHistory[]    findAll()
 * @method ProjectMemberHistory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProjectMemberHistoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProjectMemberHistory::class);
    }

    // /**
    //  * @return ProjectMemberHistory[] Returns an array of ProjectMemberHistory objects
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
    public function findOneBySomeField($value): ?ProjectMemberHistory
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
