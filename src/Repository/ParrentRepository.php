<?php

namespace App\Repository;

use App\Entity\Parrent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Parrent|null find($id, $lockMode = null, $lockVersion = null)
 * @method Parrent|null findOneBy(array $criteria, array $orderBy = null)
 * @method Parrent[]    findAll()
 * @method Parrent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ParrentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Parrent::class);
    }

    // /**
    //  * @return Parrent[] Returns an array of Parrent objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Parrent
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
