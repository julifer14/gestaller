<?php

namespace App\Repository;

use App\Entity\OrdreReparacio;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method OrdreReparacio|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrdreReparacio|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrdreReparacio[]    findAll()
 * @method OrdreReparacio[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrdreReparacioRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrdreReparacio::class);
    }

    // /**
    //  * @return OrdreReparacio[] Returns an array of OrdreReparacio objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?OrdreReparacio
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
