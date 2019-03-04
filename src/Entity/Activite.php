<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ActiviteRepository")
 */
class Activite
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $libelle;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Veterinaire", mappedBy="activites")
     */
    private $veterinaires;

    public function __construct()
    {
        $this->veterinaires = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * @return Collection|Veterinaire[]
     */
    public function getVeterinaires(): Collection
    {
        return $this->veterinaires;
    }

    public function addVeterinaire(Veterinaire $veterinaire): self
    {
        if (!$this->veterinaires->contains($veterinaire)) {
            $this->veterinaires[] = $veterinaire;
        }

        return $this;
    }

    public function removeVeterinaire(Veterinaire $veterinaire): self
    {
        if ($this->veterinaires->contains($veterinaire)) {
            $this->veterinaires->removeElement($veterinaire);
        }

        return $this;
    }
}
