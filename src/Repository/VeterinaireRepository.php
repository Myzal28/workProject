<?php

namespace App\Repository;

use App\Entity\Veterinaire;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\Query;

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
    public function nombreVeterinairesDuMois()
    {
        $ddeb = new \Datetime('first day of this month');
        $dfin = new \Datetime('last day of this month');

        return $this->createQueryBuilder('v')
            ->select('count(v.id) AS nbVetosMoisCourant')
            ->where('v.dateCreation BETWEEN :dateDebut and :dateFin')
            ->setParameter('dateDebut',$ddeb)
            ->setParameter('dateFin',$dfin)
            ->getQuery()
            ->getResult(Query::HYDRATE_SINGLE_SCALAR);
    }

    public function nombreTotalVeterinaires()
    {
        return $this->createQueryBuilder('v')
            ->select('count(v.id) AS nbTotalVetos')
            ->getQuery()
            ->getResult(Query::HYDRATE_SINGLE_SCALAR);
    }

    public function nombreDeSuivisParVeterinaire()
    {
        return $this->createQueryBuilder('v')
            ->leftJoin('v.suivis','s')
            ->select('v.id')
            ->addSelect('v.nom')
            ->addSelect('v.adresse')
            ->addSelect('v.codePostal')
            ->addSelect('v.ville')
            ->addSelect('COUNT(s.id) AS nbSuivis')
            ->groupBy('v.id')
            ->addGroupBy('v.nom')
            ->addGroupBy('v.adresse')
            ->addGroupBy('v.codePostal')
            ->addGroupBy('v.ville')
            ->getQuery()
            ->getResult();
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
