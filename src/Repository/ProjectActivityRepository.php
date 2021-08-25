<?php

namespace App\Repository;

use App\Entity\ProjectActivity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ProjectActivity|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProjectActivity|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProjectActivity[]    findAll()
 * @method ProjectActivity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProjectActivityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProjectActivity::class);
    }
    
    public function findMaxOrder($milestone)
    {
        return $this->createQueryBuilder('p')
            ->select('p, MAX(p.display_order) AS max_order')
            ->where('p.milestone = :val')
            ->setParameter('val', $milestone)
            ->orderBy('p.display_order', 'DES')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult()
        ;
    }
    

    /*
    public function findOneBySomeField($value): ?ProjectActivity
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
