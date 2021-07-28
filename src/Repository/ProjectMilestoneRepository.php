<?php

namespace App\Repository;

use App\Entity\ProjectMilestone;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ProjectMilestone|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProjectMilestone|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProjectMilestone[]    findAll()
 * @method ProjectMilestone[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProjectMilestoneRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProjectMilestone::class);
    }

    // /**
    //  * @return ProjectMilestone[] Returns an array of ProjectMilestone objects
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
    public function findOneBySomeField($value): ?ProjectMilestone
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
