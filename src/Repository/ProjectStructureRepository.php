<?php

namespace App\Repository;

use App\Entity\ProjectStructure;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ProjectStructure|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProjectStructure|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProjectStructure[]    findAll()
 * @method ProjectStructure[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProjectStructureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProjectStructure::class);
    }

    // /**
    //  * @return ProjectStructure[] Returns an array of ProjectStructure objects
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
    public function findOneBySomeField($value): ?ProjectStructure
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
