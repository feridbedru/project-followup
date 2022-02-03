<?php

namespace App\Repository;

use App\Entity\ProjectCollaborationTopic;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ProjectCollaborationTopic|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProjectCollaborationTopic|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProjectCollaborationTopic[]    findAll()
 * @method ProjectCollaborationTopic[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProjectCollaborationTopicRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProjectCollaborationTopic::class);
    }

    // /**
    //  * @return ProjectCollaborationTopic[] Returns an array of ProjectCollaborationTopic objects
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
    public function findOneBySomeField($value): ?ProjectCollaborationTopic
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
