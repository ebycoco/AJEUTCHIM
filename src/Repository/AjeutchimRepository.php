<?php

namespace App\Repository;

use App\Entity\Ajeutchim;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Ajeutchim|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ajeutchim|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ajeutchim[]    findAll()
 * @method Ajeutchim[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AjeutchimRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ajeutchim::class);
    }

    // /**
    //  * @return Ajeutchim[] Returns an array of Ajeutchim objects
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
    public function findOneBySomeField($value): ?Ajeutchim
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
