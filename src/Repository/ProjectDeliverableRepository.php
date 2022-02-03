<?php

namespace App\Repository;

use App\Entity\ProjectDeliverable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ProjectDeliverable|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProjectDeliverable|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProjectDeliverable[]    findAll()
 * @method ProjectDeliverable[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProjectDeliverableRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProjectDeliverable::class);
    }

    // /**
    //  * @return ProjectDeliverable[] Returns an array of ProjectDeliverable objects
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
    public function findOneBySomeField($value): ?ProjectDeliverable
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
