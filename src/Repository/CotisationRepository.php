<?php

namespace App\Repository;

use App\Entity\Cotisation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Cotisation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Cotisation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Cotisation[]    findAll()
 * @method Cotisation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CotisationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cotisation::class);
    }

    public function findOnMembre($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.membre = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult();
    }

    public function findCotisation()
    {
        return $this->createQueryBuilder('c')
            // ->andWhere('c.status = 0')
            ->orderBy('c.id', 'DESC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }

    public function findSolde()
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.status = 1')
            ->orderBy('c.id', 'DESC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }

    public function findEncour()
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.status = 0')
            ->orderBy('c.id', 'DESC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }
    // /**
    //  * @return Cotisation[] Returns an array of Cotisation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Cotisation
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}