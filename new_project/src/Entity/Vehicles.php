<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\VehiclesRepository")
 */
class Vehicles
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=60)
     */
    private $model;

    /**
     * @ORM\Column(type="string", length=60)
     */
    private $brand;

    /**
     * @ORM\Column(type="float")
     */
    private $mileage;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateRegister;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $dateLastCheck;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Persons", inversedBy="vehicles")
     */
    private $person;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Status", inversedBy="vehicles")
     * @ORM\JoinColumn(nullable=false)
     */
    private $status;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Collect", mappedBy="vehicle")
     */
    private $collects;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Delivery", mappedBy="vehicle")
     */
    private $deliveries;

    public function __construct()
    {
        $this->collects = new ArrayCollection();
        $this->deliveries = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(string $model): self
    {
        $this->model = $model;

        return $this;
    }

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function setBrand(string $brand): self
    {
        $this->brand = $brand;

        return $this;
    }

    public function getMileage(): ?float
    {
        return $this->mileage;
    }

    public function setMileage(float $mileage): self
    {
        $this->mileage = $mileage;

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

    public function getDateLastCheck(): ?\DateTimeInterface
    {
        return $this->dateLastCheck;
    }

    public function setDateLastCheck(?\DateTimeInterface $dateLastCheck): self
    {
        $this->dateLastCheck = $dateLastCheck;

        return $this;
    }

    public function getPerson(): ?Persons
    {
        return $this->person;
    }

    public function setPerson(?Persons $person): self
    {
        $this->person = $person;

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
     * @return Collection|Collect[]
     */
    public function getCollects(): Collection
    {
        return $this->collects;
    }

    public function addCollect(Collect $collect): self
    {
        if (!$this->collects->contains($collect)) {
            $this->collects[] = $collect;
            $collect->setVehicle($this);
        }

        return $this;
    }

    public function removeCollect(Collect $collect): self
    {
        if ($this->collects->contains($collect)) {
            $this->collects->removeElement($collect);
            // set the owning side to null (unless already changed)
            if ($collect->getVehicle() === $this) {
                $collect->setVehicle(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Delivery[]
     */
    public function getDeliveries(): Collection
    {
        return $this->deliveries;
    }

    public function addDelivery(Delivery $delivery): self
    {
        if (!$this->deliveries->contains($delivery)) {
            $this->deliveries[] = $delivery;
            $delivery->setVehicle($this);
        }

        return $this;
    }

    public function removeDelivery(Delivery $delivery): self
    {
        if ($this->deliveries->contains($delivery)) {
            $this->deliveries->removeElement($delivery);
            // set the owning side to null (unless already changed)
            if ($delivery->getVehicle() === $this) {
                $delivery->setVehicle(null);
            }
        }

        return $this;
    }
}
