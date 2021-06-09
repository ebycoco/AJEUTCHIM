<?php

namespace App\Repository;

use App\Entity\PostAjeutchim;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PostAjeutchim|null find($id, $lockMode = null, $lockVersion = null)
 * @method PostAjeutchim|null findOneBy(array $criteria, array $orderBy = null)
 * @method PostAjeutchim[]    findAll()
 * @method PostAjeutchim[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostAjeutchimRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PostAjeutchim::class);
    }
    public function pesident($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.name = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(1)
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return PostAjeutchim[] Returns an array of PostAjeutchim objects
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
    public function findOneBySomeField($value): ?PostAjeutchim
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