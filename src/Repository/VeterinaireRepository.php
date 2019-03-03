<?php

namespace App\Repository;

use App\Entity\Veterinaire;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Veterinaire|null find($id, $lockMode = null, $lockVersion = null)
 * @method Veterinaire|null findOneBy(array $criteria, array $orderBy = null)
 * @method Veterinaire[]    findAll()
 * @method Veterinaire[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VeterinaireRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Veterinaire::class);
    }

    // /**
    //  * @return Veterinaire[] Returns an array of Veterinaire objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('v.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Veterinaire
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
