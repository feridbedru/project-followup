<?php

namespace App\Repository;

use App\Entity\SponsorshipType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SponsorshipType|null find($id, $lockMode = null, $lockVersion = null)
 * @method SponsorshipType|null findOneBy(array $criteria, array $orderBy = null)
 * @method SponsorshipType[]    findAll()
 * @method SponsorshipType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SponsorshipTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SponsorshipType::class);
    }

    public function findSponsorshipType($search=null)
    {
        $qb=$this->createQueryBuilder('s');
        if($search)
            $qb->andWhere("s.name  LIKE '%".$search."%'");
            return 
            $qb->orderBy('s.name', 'ASC')
            ->getQuery();
    }
    // /**
    //  * @return SponsorshipType[] Returns an array of SponsorshipType objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SponsorshipType
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
