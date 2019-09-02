<?php

namespace App\Repository;

use App\Entity\Collect;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Collect|null find($id, $lockMode = null, $lockVersion = null)
 * @method Collect|null findOneBy(array $criteria, array $orderBy = null)
 * @method Collect[]    findAll()
 * @method Collect[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CollectRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Collect::class);
    }

    public function findWaiting($warehouse){
        $qb = $this->createQueryBuilder('q');

        $qb->where("q.dateRegister > :now")
            ->setParameter('now',date('Y-m-d 00:00:00'))
            ->andWhere('q.warehouse = :warehouse')
            ->setParameter('warehouse',$warehouse)
            ->andWhere('q.status = 4')
        ;

        return $qb->getQuery()->getResult();
    }
    // /**
    //  * @return Collect[] Returns an array of Collect objects
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
    public function findOneBySomeField($value): ?Collect
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findThisWeek(){
        $qb = $this->createQueryBuilder('q');

        $qb->where("q.dateCollect > :now")
            ->setParameter('now',date('Y-m-d 00:00:00'))
            ->groupBy("q.vehicle")
            ->andWhere("q.status = 5")
        ;

        return $qb->getQuery()->getResult();
    }

}
