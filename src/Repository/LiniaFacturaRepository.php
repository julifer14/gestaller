<?php

namespace App\Repository;

use App\Entity\LiniaFactura;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LiniaFactura|null find($id, $lockMode = null, $lockVersion = null)
 * @method LiniaFactura|null findOneBy(array $criteria, array $orderBy = null)
 * @method LiniaFactura[]    findAll()
 * @method LiniaFactura[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LiniaFacturaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LiniaFactura::class);
    }

    // /**
    //  * @return LiniaFactura[] Returns an array of LiniaFactura objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?LiniaFactura
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
