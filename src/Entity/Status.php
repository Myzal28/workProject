<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\StatusRepository")
 */
class Status
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $status;

    /**
     * @ORM\Column(type="string", length=5)
     */
    private $statusType;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Warehouses", mappedBy="status")
     */
    private $warehouses;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Vehicles", mappedBy="status")
     */
    private $vehicles;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Collect", mappedBy="status")
     */
    private $collects;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Inventory", mappedBy="status")
     */
    private $inventories;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Signup", mappedBy="status")
     */
    private $signups;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Delivery", mappedBy="status")
     */
    private $deliveries;

    public function __construct()
    {
        $this->warehouses = new ArrayCollection();
        $this->vehicles = new ArrayCollection();
        $this->collects = new ArrayCollection();
        $this->inventories = new ArrayCollection();
        $this->signups = new ArrayCollection();
        $this->deliveries = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getStatusType(): ?string
    {
        return $this->statusType;
    }

    public function setStatusType(string $statusType): self
    {
        $this->statusType = $statusType;

        return $this;
    }

    /**
     * @return Collection|Warehouses[]
     */
    public function getWarehouses(): Collection
    {
        return $this->warehouses;
    }

    public function addWarehouse(Warehouses $warehouse): self
    {
        if (!$this->warehouses->contains($warehouse)) {
            $this->warehouses[] = $warehouse;
            $warehouse->setStatus($this);
        }

        return $this;
    }

    public function removeWarehouse(Warehouses $warehouse): self
    {
        if ($this->warehouses->contains($warehouse)) {
            $this->warehouses->removeElement($warehouse);
            // set the owning side to null (unless already changed)
            if ($warehouse->getStatus() === $this) {
                $warehouse->setStatus(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Vehicles[]
     */
    public function getVehicles(): Collection
    {
        return $this->vehicles;
    }

    public function addVehicle(Vehicles $vehicle): self
    {
        if (!$this->vehicles->contains($vehicle)) {
            $this->vehicles[] = $vehicle;
            $vehicle->setStatus($this);
        }

        return $this;
    }

    public function removeVehicle(Vehicles $vehicle): self
    {
        if ($this->vehicles->contains($vehicle)) {
            $this->vehicles->removeElement($vehicle);
            // set the owning side to null (unless already changed)
            if ($vehicle->getStatus() === $this) {
                $vehicle->setStatus(null);
            }
        }

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
            $collect->setStatus($this);
        }

        return $this;
    }

    public function removeCollect(Collect $collect): self
    {
        if ($this->collects->contains($collect)) {
            $this->collects->removeElement($collect);
            // set the owning side to null (unless already changed)
            if ($collect->getStatus() === $this) {
                $collect->setStatus(null);
            }
        }

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
            $inventory->setStatus($this);
        }

        return $this;
    }

    public function removeInventory(Inventory $inventory): self
    {
        if ($this->inventories->contains($inventory)) {
            $this->inventories->removeElement($inventory);
            // set the owning side to null (unless already changed)
            if ($inventory->getStatus() === $this) {
                $inventory->setStatus(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Signup[]
     */
    public function getSignups(): Collection
    {
        return $this->signups;
    }

    public function addSignup(Signup $signup): self
    {
        if (!$this->signups->contains($signup)) {
            $this->signups[] = $signup;
            $signup->setStatus($this);
        }

        return $this;
    }

    public function removeSignup(Signup $signup): self
    {
        if ($this->signups->contains($signup)) {
            $this->signups->removeElement($signup);
            // set the owning side to null (unless already changed)
            if ($signup->getStatus() === $this) {
                $signup->setStatus(null);
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
            $delivery->setStatus($this);
        }

        return $this;
    }

    public function removeDelivery(Delivery $delivery): self
    {
        if ($this->deliveries->contains($delivery)) {
            $this->deliveries->removeElement($delivery);
            // set the owning side to null (unless already changed)
            if ($delivery->getStatus() === $this) {
                $delivery->setStatus(null);
            }
        }

        return $this;
    }
}
