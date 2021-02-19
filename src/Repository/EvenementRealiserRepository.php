<?php

namespace App\Repository;

use App\Entity\EvenementRealiser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method EvenementRealiser|null find($id, $lockMode = null, $lockVersion = null)
 * @method EvenementRealiser|null findOneBy(array $criteria, array $orderBy = null)
 * @method EvenementRealiser[]    findAll()
 * @method EvenementRealiser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EvenementRealiserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EvenementRealiser::class);
    }

    // /**
    //  * @return EvenementRealiser[] Returns an array of EvenementRealiser objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?EvenementRealiser
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
