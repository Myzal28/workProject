<?php

namespace App\Repository;
use App\Entity\Persons;
use App\Entity\Guarding;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Guarding|null find($id, $lockMode = null, $lockVersion = null)
 * @method Guarding|null findOneBy(array $criteria, array $orderBy = null)
 * @method Guarding[]    findAll()
 * @method Guarding[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GuardingRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Guarding::class);
    }

    public function findForIntern(Persons $user){
        $qb = $this->createQueryBuilder('q');

        $qb->where($qb->expr()->eq('q.userGuarding',$user->getId()))
            ->orWhere('q.userGuarding is NULL')
            ->orderBy('q.date','DESC')
        ;
        return $qb->getQuery()->getResult();
    }
    // /**
    //  * @return Guarding[] Returns an array of Guarding objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Guarding
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findThisWeek(){
        $qb = $this->createQueryBuilder('q');

        $qb->where("q.beginning > :now")
            ->setParameter('now',date('Y-m-d 00:00:00'))
        ;

        return $qb->getQuery()->getResult();
    }


}
