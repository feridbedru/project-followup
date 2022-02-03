<?php

namespace App\Repository;

use App\Entity\ProjectPlanComment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ProjectPlanComment|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProjectPlanComment|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProjectPlanComment[]    findAll()
 * @method ProjectPlanComment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProjectPlanCommentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProjectPlanComment::class);
    }

    public function findComments($project, $date)
    {
        return $this->createQueryBuilder("r")
            ->Where('r.created_at > :dt')
            ->andWhere('r.project = :project')
            ->setParameter('dt', $date)
            ->setParameter('project', $project)
            ->getQuery()
            ->getResult();
    }
    // /**
    //  * @return ProjectPlanComment[] Returns an array of ProjectPlanComment objects
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
    public function findOneBySomeField($value): ?ProjectPlanComment
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
