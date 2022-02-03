<?php

namespace App\Repository;

use App\Entity\ProjectVersion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ProjectVersion|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProjectVersion|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProjectVersion[]    findAll()
 * @method ProjectVersion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProjectVersionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProjectVersion::class);
    }

    public function findProjectVersion($search=null)
    {
        $qb=$this->createQueryBuilder('p');
        if($search)
            $qb->andWhere("p.version  LIKE '%".$search."%'");
            return 
            $qb->orderBy('p.version', 'ASC')
            ->getQuery();
    }
    // /**
    //  * @return ProjectVersion[] Returns an array of ProjectVersion objects
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
    public function findOneBySomeField($value): ?ProjectVersion
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
