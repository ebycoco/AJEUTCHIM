<?php

namespace App\Repository;

use App\Entity\Candidat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Candidat|null find($id, $lockMode = null, $lockVersion = null)
 * @method Candidat|null findOneBy(array $criteria, array $orderBy = null)
 * @method Candidat[]    findAll()
 * @method Candidat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CandidatRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Candidat::class);
    }

    public function findCandidat($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.candidature = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'DESC')
            ->getQuery()
            ->getResult();
    }
    public function findCandidatAvote()
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.etat = false')
            ->orderBy('c.id', 'DESC')
            ->getQuery()
            ->getResult();
    }
    public function findCandidatAnnee($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.annee = :val')
            ->setParameter('val', $value)
            ->orderBy('c.nombreVoix', 'DESC')
            ->getQuery()
            ->getResult();
    }
    public function findCandidatAnneeTour2($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.tour2 = :val')
            ->setParameter('val', $value)
            ->orderBy('c.nombreVoix', 'DESC')
            ->getQuery()
            ->getResult();
    }
    public function findCandidatSecond($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.nombreVoix = :val')
            ->setParameter('val', $value)
            ->orderBy('c.nombreVoix', 'DESC')
            ->getQuery()
            ->getResult();
    }
    public function findCandidatPlusPoint($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.annee = :val')
            ->setParameter('val', $value)
            ->orderBy('c.nombreVoix', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getResult();
    }
    // /**
    //  * @return Candidat[] Returns an array of Candidat objects
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
    public function findOneBySomeField($value): ?Candidat
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
