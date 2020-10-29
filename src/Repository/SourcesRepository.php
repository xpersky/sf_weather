<?php

namespace App\Repository;

use App\Entity\Sources;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Sources|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sources|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sources[]    findAll()
 * @method Sources[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SourcesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sources::class);
    }

    public function findAllSources()
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.iscord <> 1')
            ->orWhere('s.iscord is NULL')
            ->getQuery()
            ->getResult();
    }

    public function findCords()
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.iscord = 1')
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return Sources[] Returns an array of Sources objects
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
    public function findOneBySomeField($value): ?Sources
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
