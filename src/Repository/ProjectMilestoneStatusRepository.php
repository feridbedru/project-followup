<?php

namespace App\Repository;

use App\Entity\ProjectMilestoneStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ProjectMilestoneStatus|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProjectMilestoneStatus|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProjectMilestoneStatus[]    findAll()
 * @method ProjectMilestoneStatus[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProjectMilestoneStatusRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProjectMilestoneStatus::class);
    }

    // /**
    //  * @return ProjectMilestoneStatus[] Returns an array of ProjectMilestoneStatus objects
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
    public function findOneBySomeField($value): ?ProjectMilestoneStatus
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
