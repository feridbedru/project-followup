<?php

namespace App\Repository;

use App\Entity\ProjectResource;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ProjectResource|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProjectResource|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProjectResource[]    findAll()
 * @method ProjectResource[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProjectResourceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProjectResource::class);
    }

    // /**
    //  * @return ProjectResource[] Returns an array of ProjectResource objects
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
    public function findOneBySomeField($value): ?ProjectResource
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
