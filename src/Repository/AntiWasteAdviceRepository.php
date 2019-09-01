<?php

namespace App\Repository;

use App\Entity\AntiWasteAdvice;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method AntiWasteAdvice|null find($id, $lockMode = null, $lockVersion = null)
 * @method AntiWasteAdvice|null findOneBy(array $criteria, array $orderBy = null)
 * @method AntiWasteAdvice[]    findAll()
 * @method AntiWasteAdvice[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AntiWasteAdviceRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, AntiWasteAdvice::class);
    }
    public function findAllById()
    {
        return $this->findBy(array(), array('id' => 'DESC'));
    }

    public function findAllByUpvotes(){
        $qb = $this->createQueryBuilder('advices')
            ->leftJoin('advices.upvoted','upvoted')
            ->select('COUNT(upvoted) AS HIDDEN upvotes','advices')
            ->orderBy('upvotes','DESC')
            ->groupBy('advices')
            ->getQuery()
            ->getResult()
        ;
        return $qb;
    }
    // /**
    //  * @return AntiWasteAdvice[] Returns an array of AntiWasteAdvice objects
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
    public function findOneBySomeField($value): ?AntiWasteAdvice
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
