<?php

namespace App\Repository;

use App\Entity\Desactive;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Desactiver|null find($id, $lockMode = null, $lockVersion = null)
 * @method Desactiver|null findOneBy(array $criteria, array $orderBy = null)
 * @method Desactiver[]    findAll()
 * @method Desactiver[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DesactiveRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Desactive::class);
    }

    // /**
    //  * @return Desactiver[] Returns an array of Desactiver objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Desactiver
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}