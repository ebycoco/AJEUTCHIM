<?php

namespace App\Repository;

use App\Entity\Depense;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Depense|null find($id, $lockMode = null, $lockVersion = null)
 * @method Depense|null findOneBy(array $criteria, array $orderBy = null)
 * @method Depense[]    findAll()
 * @method Depense[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DepenseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Depense::class);
    }

    public function findByEncour()
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.confirme = 1')
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }

    public function findByAnne($value, $value1)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.annee =  :val')
            ->andWhere('d.visible =  false')
            ->orWhere('d.etat =  :val1')
            ->setParameter('val', $value)
            ->setParameter('val1', $value1)
            ->orderBy('d.id', 'DESC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }
    public function findByTerminer()
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.visible =  false')
            ->andWhere('d.etat =  2')
            ->orderBy('d.id', 'DESC')
            ->getQuery()
            ->getResult();
    }
    public function findByEncou()
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.visible =  false')
            ->andWhere('d.rejeter =  0')
            ->andWhere('d.confirme =  true')
            ->andWhere('d.etat =  1')
            ->orderBy('d.id', 'DESC')
            ->getQuery()
            ->getResult();
    }
    public function findOnMembre($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.user = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'DESC')
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return Depense[] Returns an array of Depense objects
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
    public function findOneBySomeField($value): ?Depense
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