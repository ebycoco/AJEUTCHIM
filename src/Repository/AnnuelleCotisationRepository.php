<?php

namespace App\Repository;

use App\Entity\AnnuelleCotisation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AnnuelleCotisation|null find($id, $lockMode = null, $lockVersion = null)
 * @method AnnuelleCotisation|null findOneBy(array $criteria, array $orderBy = null)
 * @method AnnuelleCotisation[]    findAll()
 * @method AnnuelleCotisation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnnuelleCotisationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AnnuelleCotisation::class);
    }

    // /**
    //  * @return AnnuelleCotisation[] Returns an array of AnnuelleCotisation objects
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
    public function findOneBySomeField($value): ?AnnuelleCotisation
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
