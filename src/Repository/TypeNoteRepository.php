<?php

namespace App\Repository;

use App\Entity\TypeNote;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TypeNote|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypeNote|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypeNote[]    findAll()
 * @method TypeNote[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypeNoteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TypeNote::class);
    }

    // /**
    //  * @return TypeNote[] Returns an array of TypeNote objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TypeNote
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
