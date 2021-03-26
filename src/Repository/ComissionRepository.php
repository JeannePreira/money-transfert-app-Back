<?php

namespace App\Repository;

use App\Entity\Comission;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Comission|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comission|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comission[]    findAll()
 * @method Comission[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ComissionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comission::class);
    }

    // /**
    //  * @return Comission[] Returns an array of Comission objects
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
    public function findOneBySomeField($value): ?Comission
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
