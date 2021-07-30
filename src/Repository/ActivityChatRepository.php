<?php

namespace App\Repository;

use App\Entity\ActivityChat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ActivityChat|null find($id, $lockMode = null, $lockVersion = null)
 * @method ActivityChat|null findOneBy(array $criteria, array $orderBy = null)
 * @method ActivityChat[]    findAll()
 * @method ActivityChat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ActivityChatRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ActivityChat::class);
    }

    // /**
    //  * @return ActivityChat[] Returns an array of ActivityChat objects
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
    public function findOneBySomeField($value): ?ActivityChat
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
