<?php

namespace App\Repository;

use App\Entity\Decaisement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Decaisement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Decaisement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Decaisement[]    findAll()
 * @method Decaisement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DecaisementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Decaisement::class);
    }

    // /**
    //  * @return Decaisement[] Returns an array of Decaisement objects
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
    public function findOneBySomeField($value): ?Decaisement
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
