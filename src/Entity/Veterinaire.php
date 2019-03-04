<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\VeterinaireRepository")
 */
class Veterinaire
{
    /**
     * Veterinaire constructor.
     * @throws \Exception
     */
    public function __construct(){
        $this->dateCreation = new \Datetime();
        $this->suivis = new ArrayCollection();
        $this->activites = new ArrayCollection();
        $this->objectifs = new ArrayCollection();
    }
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=80)
     * @Assert\Length (
     * min = "10",
     * max = "80",
     * minMessage = "{{ limit }} caractères minimum",
     * maxMessage = "{{ limit }} caractères maximum"
     * )
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\Length (
     * max = "80",
     * maxMessage = "{{ limit }} caractères maximum"
     * )
     */
    private $adresse;

    /**
     * @ORM\Column(type="string", length=5)
     * @Assert\Regex(
     * pattern = "/^(0[1-9]|[1-9][0-9])[0-9]{3}$/",
     * match = "false",
     * message = "Ce code postal n'existe pas"
     * )
     */
    private $codePostal;

    /**
     * @ORM\Column(type="string", length=80)
     */
    private $ville;

    /**
     * @ORM\Column(type="string", length=15)
     * @Assert\Regex(
     * pattern = "/^(0|(\\+33)|(0033))[1-9][0-9]{8}$/",
     * match = "false",
     * message = "Veuillez saisir un numéro de téléphone valide"
     * )
     */
    private $telephone;

    /**
     * @ORM\Column(type="date")
     */
    private $dateCreation;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Photo", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $photo;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Suivi", mappedBy="veterinaire", orphanRemoval=true)
     */
    private $suivis;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Activite",inversedBy="veterinaires")
     */
    private $activites;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Objectif", mappedBy="veterinaire", orphanRemoval=true)
     */
    private $objectifs;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getCodePostal(): ?string
    {
        return $this->codePostal;
    }

    public function setCodePostal(string $codePostal): self
    {
        $this->codePostal = $codePostal;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeInterface $dateCreation): self
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    public function getPhoto(): ?Photo
    {
        return $this->photo;
    }

    public function setPhoto(Photo $photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * @return Collection|Suivi[]
     */
    public function getSuivis(): Collection
    {
        return $this->suivis;
    }

    public function addSuivi(Suivi $suivi): self
    {
        if (!$this->suivis->contains($suivi)) {
            $this->suivis[] = $suivi;
            $suivi->setVeterinaire($this);
        }

        return $this;
    }

    public function removeSuivi(Suivi $suivi): self
    {
        if ($this->suivis->contains($suivi)) {
            $this->suivis->removeElement($suivi);
            // set the owning side to null (unless already changed)
            if ($suivi->getVeterinaire() === $this) {
                $suivi->setVeterinaire(null);
            }
        }

        return $this;
    }

    public function getNomVille()
    {
        return $this->nom . " - " . $this->ville;
    }

    /**
     * @return Collection|Activite[]
     */
    public function getActivites(): Collection
    {
        return $this->activites;
    }

    public function addActivite(Activite $activite): self
    {
        if (!$this->activites->contains($activite)) {
            $this->activites[] = $activite;
        }

        return $this;
    }

    public function removeActivite(Activite $activite): self
    {
        if ($this->activites->contains($activite)) {
            $this->activites->removeElement($activite);
        }

        return $this;
    }

    /**
     * @return Collection|Objectif[]
     */
    public function getObjectifs(): Collection
    {
        return $this->objectifs;
    }

    public function addObjectif(Objectif $objectif): self
    {
        if (!$this->objectifs->contains($objectif)) {
            $this->objectifs[] = $objectif;
            $objectif->setVeterinaire($this);
        }

        return $this;
    }

    public function removeObjectif(Objectif $objectif): self
    {
        if ($this->objectifs->contains($objectif)) {
            $this->objectifs->removeElement($objectif);
            // set the owning side to null (unless already changed)
            if ($objectif->getVeterinaire() === $this) {
                $objectif->setVeterinaire(null);
            }
        }

        return $this;
    }
}
