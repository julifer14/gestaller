<?php

namespace App\Repository;

use App\Entity\Tasca;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Tasca|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tasca|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tasca[]    findAll()
 * @method Tasca[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TascaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tasca::class);
    }

    // /**
    //  * @return Tasca[] Returns an array of Tasca objects
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
    public function findOneBySomeField($value): ?Tasca
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
