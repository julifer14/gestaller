<?php

namespace App\Repository;

use App\Entity\LiniaPressupost;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LiniaPressupost|null find($id, $lockMode = null, $lockVersion = null)
 * @method LiniaPressupost|null findOneBy(array $criteria, array $orderBy = null)
 * @method LiniaPressupost[]    findAll()
 * @method LiniaPressupost[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LiniaPressupostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LiniaPressupost::class);
    }

    // /**
    //  * @return LiniaPressupost[] Returns an array of LiniaPressupost objects
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
    public function findOneBySomeField($value): ?LiniaPressupost
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
