<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
/**
 * @ORM\Entity(repositoryClass="App\Repository\ObjectifRepository")
 * @UniqueEntity(
 *     fields={"veterinaire","produit"},
 *     errorPath="produit",
 *     message="Un objectif est déjà défini pour ce produit"
 * )
 * @ORM\Table(
 * name="objectif",
 * uniqueConstraints={@ORM\UniqueConstraint(
 * name="UQ_veterinaire_produit",
 * columns={"veterinaire_id", "produit_id"}
 * )}
 * )

 */
class Objectif
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    private $montant;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Veterinaire", inversedBy="objectifs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $veterinaire;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Produit", inversedBy="objectifs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $produit;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMontant()
    {
        return $this->montant;
    }

    public function setMontant($montant): self
    {
        $this->montant = $montant;

        return $this;
    }

    public function getVeterinaire(): ?Veterinaire
    {
        return $this->veterinaire;
    }

    public function setVeterinaire(?Veterinaire $veterinaire): self
    {
        $this->veterinaire = $veterinaire;

        return $this;
    }

    public function getProduit(): ?Produit
    {
        return $this->produit;
    }

    public function setProduit(?Produit $produit): self
    {
        $this->produit = $produit;

        return $this;
    }

    public function getNomProduit()
    {
        return $this->produit->getNom();
    }

    public function getPrixProduit()
    {
        return $this->produit->getPrix();
    }
}
