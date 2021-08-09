<?php

namespace App\Repository;

use App\Entity\EmploisDuTepms;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method EmploisDuTepms|null find($id, $lockMode = null, $lockVersion = null)
 * @method EmploisDuTepms|null findOneBy(array $criteria, array $orderBy = null)
 * @method EmploisDuTepms[]    findAll()
 * @method EmploisDuTepms[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EmploisDuTepmsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EmploisDuTepms::class);
    }

    // /**
    //  * @return EmploisDuTepms[] Returns an array of EmploisDuTepms objects
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
    public function findOneBySomeField($value): ?EmploisDuTepms
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
