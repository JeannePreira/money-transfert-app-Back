<?php

namespace App\Repository;

use App\Entity\InfoTransaction;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method InfoTransaction|null find($id, $lockMode = null, $lockVersion = null)
 * @method InfoTransaction|null findOneBy(array $criteria, array $orderBy = null)
 * @method InfoTransaction[]    findAll()
 * @method InfoTransaction[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InfoTransactionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InfoTransaction::class);
    }

    // /**
    //  * @return InfoTransaction[] Returns an array of InfoTransaction objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?InfoTransaction
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
