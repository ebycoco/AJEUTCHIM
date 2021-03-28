<?php

namespace App\Repository;

use App\Entity\DemandeAdhesion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DemandeAdhesion|null find($id, $lockMode = null, $lockVersion = null)
 * @method DemandeAdhesion|null findOneBy(array $criteria, array $orderBy = null)
 * @method DemandeAdhesion[]    findAll()
 * @method DemandeAdhesion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DemandeAdhesionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DemandeAdhesion::class);
    }

    // /**
    //  * @return DemandeAdhesion[] Returns an array of DemandeAdhesion objects
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
    public function findOneBySomeField($value): ?DemandeAdhesion
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
