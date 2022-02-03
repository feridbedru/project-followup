<?php

namespace App\Repository;

use App\Entity\ProjectPlanRevision;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ProjectPlanRevision|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProjectPlanRevision|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProjectPlanRevision[]    findAll()
 * @method ProjectPlanRevision[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProjectPlanRevisionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProjectPlanRevision::class);
    }

    public function findMax($project=null)
    {
        return $this->createQueryBuilder('p')
            ->select('MAX(p.revision_id) AS max')
            ->andWhere("p.project = :project")
            ->setParameter('project', $project)
            ->orderBy('p.revision_id', 'DESC')
            ->groupBy('p.project')
            ->getQuery()
            ->getSingleResult()
        ;
    }

    public function findLastRevisionDate($project)
    {
        return $this->createQueryBuilder('p')
            ->select('p.created_at, MAX(p.revision_id) AS max')
            ->andWhere("p.project = :project")
            ->setParameter('project', $project)
            ->orderBy('p.revision_id', 'DESC')
            ->groupBy('p.project')
            ->getQuery()
            ->getSingleResult()
        ;
    }
    // /**
    //  * @return ProjectPlanRevision[] Returns an array of ProjectPlanRevision objects
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
    public function findOneBySomeField($value): ?ProjectPlanRevision
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
