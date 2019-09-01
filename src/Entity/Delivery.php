<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DeliveryRepository")
 */
class Delivery
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateDelivery;

    /**
     * @ORM\Column(type="string", length=120)
     */
    private $entityFirstname;

    /**
     * @ORM\Column(type="string", length=120)
     */
    private $entityLastname;

    /**
     * @ORM\Column(type="string", length=120)
     */
    private $entityEmail;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=120)
     */
    private $country;

    /**
     * @ORM\Column(type="string", length=60)
     */
    private $zipcode;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Status", inversedBy="deliveries")
     * @ORM\JoinColumn(nullable=false)
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Vehicles", inversedBy="deliveries")
     */
    private $vehicle;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Inventory", mappedBy="delivery")
     */
    private $inventory;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $city;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Warehouses")
     * @ORM\JoinColumn(nullable=false)
     */
    private $warehouse;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $latitude;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $longitude;

    public function __construct()
    {
        $this->inventory = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateDelivery(): ?\DateTimeInterface
    {
        return $this->dateDelivery;
    }

    public function setDateDelivery(?\DateTimeInterface $dateDelivery): self
    {
        $this->dateDelivery = $dateDelivery;

        return $this;
    }

    public function getEntityFirstname(): ?string
    {
        return $this->entityFirstname;
    }

    public function setEntityFirstname(string $entityFirstname): self
    {
        $this->entityFirstname = $entityFirstname;

        return $this;
    }

    public function getEntityLastname(): ?string
    {
        return $this->entityLastname;
    }

    public function setEntityLastname(string $entityLastname): self
    {
        $this->entityLastname = $entityLastname;

        return $this;
    }

    public function getEntityEmail(): ?string
    {
        return $this->entityEmail;
    }

    public function setEntityEmail(string $entityEmail): self
    {
        $this->entityEmail = $entityEmail;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getZipcode(): ?string
    {
        return $this->zipcode;
    }

    public function setZipcode(string $zipcode): self
    {
        $this->zipcode = $zipcode;

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

    public function getVehicle(): ?Vehicles
    {
        return $this->vehicle;
    }

    public function setVehicle(?Vehicles $vehicle): self
    {
        $this->vehicle = $vehicle;

        return $this;
    }

    /**
     * @return Collection|Inventory[]
     */
    public function getInventory(): Collection
    {
        return $this->inventory;
    }

    public function addInventory(Inventory $inventory): self
    {
        if (!$this->inventory->contains($inventory)) {
            $this->inventory[] = $inventory;
            $inventory->setDelivery($this);
        }

        return $this;
    }

    public function removeInventory(Inventory $inventory): self
    {
        if ($this->inventory->contains($inventory)) {
            $this->inventory->removeElement($inventory);
            // set the owning side to null (unless already changed)
            if ($inventory->getDelivery() === $this) {
                $inventory->setDelivery(null);
            }
        }

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getWarehouse(): ?Warehouses
    {
        return $this->warehouse;
    }

    public function setWarehouse(?Warehouses $warehouse): self
    {
        $this->warehouse = $warehouse;

        return $this;
    }

    public function getLatitude(): ?string
    {
        return $this->latitude;
    }

    public function setLatitude(string $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?string
    {
        return $this->longitude;
    }

    public function setLongitude(string $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }
}
