<?php

namespace App\Repository;

use App\Entity\Autredepense;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Autredepense|null find($id, $lockMode = null, $lockVersion = null)
 * @method Autredepense|null findOneBy(array $criteria, array $orderBy = null)
 * @method Autredepense[]    findAll()
 * @method Autredepense[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AutredepenseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Autredepense::class);
    }
    public function findAllAutreDepense($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.annee = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return Autredepense[] Returns an array of Autredepense objects
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
    public function findOneBySomeField($value): ?Autredepense
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