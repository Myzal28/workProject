<?php

namespace App\Repository;

use App\Entity\Foods;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Foods|null find($id, $lockMode = null, $lockVersion = null)
 * @method Foods|null findOneBy(array $criteria, array $orderBy = null)
 * @method Foods[]    findAll()
 * @method Foods[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FoodsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Foods::class);
    }

    // /**
    //  * @return Foods[] Returns an array of Foods objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Foods
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
