<?php

namespace App\Repository;

use App\Entity\Any;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Any|null find($id, $lockMode = null, $lockVersion = null)
 * @method Any|null findOneBy(array $criteria, array $orderBy = null)
 * @method Any[]    findAll()
 * @method Any[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Any::class);
    }

    // /**
    //  * @return Any[] Returns an array of Any objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Any
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
