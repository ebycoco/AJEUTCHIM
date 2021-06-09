<?php

namespace App\Repository;

use App\Entity\FonctionAjeutchim;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FonctionAjeutchim|null find($id, $lockMode = null, $lockVersion = null)
 * @method FonctionAjeutchim|null findOneBy(array $criteria, array $orderBy = null)
 * @method FonctionAjeutchim[]    findAll()
 * @method FonctionAjeutchim[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FonctionAjeutchimRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FonctionAjeutchim::class);
    }

    // /**
    //  * @return FonctionAjeutchim[] Returns an array of FonctionAjeutchim objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?FonctionAjeutchim
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
