<?php

namespace App\Repository;

use App\Entity\CookingClass;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CookingClass|null find($id, $lockMode = null, $lockVersion = null)
 * @method CookingClass|null findOneBy(array $criteria, array $orderBy = null)
 * @method CookingClass[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CookingClassRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CookingClass::class);
    }
    public function findAll()
    {
        return $this->findBy(array(), array('beginning' => 'DESC'));
    }

    // /**
    //  * @return CookingClass[] Returns an array of CookingClass objects
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
    public function findOneBySomeField($value): ?CookingClass
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
