<?php

namespace App\Repository;

use App\Entity\IndividualOffer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\PropertyInfo\DependencyInjection\PropertyInfoPass;

/**
 * @method IndividualOffer|null find($id, $lockMode = null, $lockVersion = null)
 * @method IndividualOffer|null findOneBy(array $criteria, array $orderBy = null)
 * @method IndividualOffer[]    findAll()
 * @method IndividualOffer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IndividualOfferRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, IndividualOffer::class);
    }
}
