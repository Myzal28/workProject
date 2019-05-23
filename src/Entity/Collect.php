<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CollectRepository")
 */
class Collect
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $commentary;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateRegister;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateCollect;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Persons", inversedBy="collectsCheck")
     */
    private $personCheck;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Persons", inversedBy="collectsCreate")
     * @ORM\JoinColumn(nullable=false)
     */
    private $personCreate;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Vehicles", inversedBy="collects")
     */
    private $vehicle;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Status", inversedBy="collects")
     * @ORM\JoinColumn(nullable=false)
     */
    private $status;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Inventory", mappedBy="collect", orphanRemoval=true)
     */
    private $inventories;

    public function __construct()
    {
        $this->inventories = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCommentary(): ?string
    {
        return $this->commentary;
    }

    public function setCommentary(string $commentary): self
    {
        $this->commentary = $commentary;

        return $this;
    }

    public function getDateRegister(): ?\DateTimeInterface
    {
        return $this->dateRegister;
    }

    public function setDateRegister(\DateTimeInterface $dateRegister): self
    {
        $this->dateRegister = $dateRegister;

        return $this;
    }

    public function getDateCollect(): ?\DateTimeInterface
    {
        return $this->dateCollect;
    }

    public function setDateCollect(\DateTimeInterface $dateCollect): self
    {
        $this->dateCollect = $dateCollect;

        return $this;
    }

    public function getPersonCheck(): ?Persons
    {
        return $this->personCheck;
    }

    public function setPersonCheck(?Persons $personCheck): self
    {
        $this->personCheck = $personCheck;

        return $this;
    }

    public function getPersonCreate(): ?Persons
    {
        return $this->personCreate;
    }

    public function setPersonCreate(?Persons $personCreate): self
    {
        $this->personCreate = $personCreate;

        return $this;
    }

    public function getVehicle(): ?Vehicles
    {
        return $this->vehicle;
    }

    public function setVehicle(?Vehicles $vehicle): self
    {
        $this->vehicle = $vehicle;

        return $this;
    }

    public function getStatus(): ?Status
    {
        return $this->status;
    }

    public function setStatus(?Status $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection|Inventory[]
     */
    public function getInventories(): Collection
    {
        return $this->inventories;
    }

    public function addInventory(Inventory $inventory): self
    {
        if (!$this->inventories->contains($inventory)) {
            $this->inventories[] = $inventory;
            $inventory->setCollect($this);
        }

        return $this;
    }

    public function removeInventory(Inventory $inventory): self
    {
        if ($this->inventories->contains($inventory)) {
            $this->inventories->removeElement($inventory);
            // set the owning side to null (unless already changed)
            if ($inventory->getCollect() === $this) {
                $inventory->setCollect(null);
            }
        }

        return $this;
    }
}
