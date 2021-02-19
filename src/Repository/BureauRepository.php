<?php

namespace App\Repository;

use App\Entity\Bureau;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Bureau|null find($id, $lockMode = null, $lockVersion = null)
 * @method Bureau|null findOneBy(array $criteria, array $orderBy = null)
 * @method Bureau[]    findAll()
 * @method Bureau[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BureauRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Bureau::class);
    }

    public function findPostOcupe($value, $value1)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.president = :val')
            ->andWhere('b.postAjeutchim = :val1')
            ->setParameter('val', $value)
            ->setParameter('val1', $value1)
            ->getQuery()
            ->getResult();
    }
    public function findMembreBureau($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.president = :val')
            ->setParameter('val', $value)
            ->orderBy('b.postAjeutchim', 'ASC')
            ->getQuery()
            ->getResult();
    }



    public function findPostMembreOccupe($value, $value1)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.president = :val')
            ->andWhere('b.membre = :val1')
            ->setParameter('val', $value)
            ->setParameter('val1', $value1)
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return Bureau[] Returns an array of Bureau objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Bureau
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}