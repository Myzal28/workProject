<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SuiviRepository")
 */
class Suivi
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length (
     * max = "40",
     * maxMessage = "{{ limit }} caractères maximum"
     * )
     */
    private $nomContact;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length (
     * max = "250",
     * maxMessage = "{{ limit }} caractères maximum"
     * )
     */
    private $commentaire;

    /**
     * @ORM\Column(type="date")
     */
    private $dateAppel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Veterinaire", inversedBy="suivis")
     * @ORM\JoinColumn(nullable=false)
     */
    private $veterinaire;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomContact(): ?string
    {
        return $this->nomContact;
    }

    public function setNomContact(string $nomContact): self
    {
        $this->nomContact = $nomContact;

        return $this;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(string $commentaire): self
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    public function getDateAppel(): ?\DateTimeInterface
    {
        return $this->dateAppel;
    }

    public function setDateAppel(\DateTimeInterface $dateAppel): self
    {
        $this->dateAppel = $dateAppel;

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
}
