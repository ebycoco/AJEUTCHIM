<?php

namespace App\Repository;

use App\Entity\MembreConseil;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MembreConseil|null find($id, $lockMode = null, $lockVersion = null)
 * @method MembreConseil|null findOneBy(array $criteria, array $orderBy = null)
 * @method MembreConseil[]    findAll()
 * @method MembreConseil[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MembreConseilRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MembreConseil::class);
    }

    // /**
    //  * @return MembreConseil[] Returns an array of MembreConseil objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MembreConseil
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
