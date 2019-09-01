<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CookingClassRepository")
 */
class CookingClass
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
    private $name;

    /**
     * @ORM\Column(type="datetime")
     */
    private $beginning;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Persons", inversedBy="cookingClasses")
     * @ORM\JoinColumn(nullable=false)
     */
    private $professor;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $place;

    /**
     * @ORM\Column(type="integer")
     */
    private $duration;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Persons", inversedBy="registeredToCookingClass")
     */
    private $registeredPeople;

    /**
     * @ORM\Column(type="integer")
     */
    private $capacity;

    public function __construct()
    {
        $this->registeredPeople = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getBeginning(): ?\DateTimeInterface
    {
        return $this->beginning;
    }

    public function setBeginning(\DateTimeInterface $beginning): self
    {
        $this->beginning = $beginning;

        return $this;
    }

    public function getProfessor(): ?Persons
    {
        return $this->professor;
    }

    public function setProfessor(?Persons $professor): self
    {
        $this->professor = $professor;

        return $this;
    }

    public function getPlace(): ?string
    {
        return $this->place;
    }

    public function setPlace(string $place): self
    {
        $this->place = $place;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * @return Collection|Persons[]
     */
    public function getRegisteredPeople(): Collection
    {
        return $this->registeredPeople;
    }

    public function addRegisteredPerson(Persons $registeredPerson): self
    {
        if (!$this->registeredPeople->contains($registeredPerson)) {
            $this->registeredPeople[] = $registeredPerson;
        }

        return $this;
    }

    public function removeRegisteredPerson(Persons $registeredPerson): self
    {
        if ($this->registeredPeople->contains($registeredPerson)) {
            $this->registeredPeople->removeElement($registeredPerson);
        }

        return $this;
    }

    public function getCapacity(): ?int
    {
        return $this->capacity;
    }

    public function setCapacity(int $capacity): self
    {
        $this->capacity = $capacity;

        return $this;
    }
}
