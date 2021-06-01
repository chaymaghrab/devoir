<?php

namespace App\Repository;

use App\Entity\Calegorie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Calegorie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Calegorie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Calegorie[]    findAll()
 * @method Calegorie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CalegorieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Calegorie::class);
    }

    // /**
    //  * @return Calegorie[] Returns an array of Calegorie objects
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
    public function findOneBySomeField($value): ?Calegorie
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
