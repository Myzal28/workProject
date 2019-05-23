<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\InventoryRepository")
 */
class Inventory
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $dateExpire;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateRegister;

    /**
     * @ORM\Column(type="text")
     */
    private $commentary;

    /**
     * @ORM\Column(type="integer")
     */
    private $number;

    /**
     * @ORM\Column(type="float")
     */
    private $weight;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Foods", inversedBy="inventories")
     * @ORM\JoinColumn(nullable=false)
     */
    private $foods;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Persons", inversedBy="inventories")
     * @ORM\JoinColumn(nullable=false)
     */
    private $personCreate;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Status", inversedBy="inventories")
     * @ORM\JoinColumn(nullable=false)
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Collect", inversedBy="inventories")
     * @ORM\JoinColumn(nullable=false)
     */
    private $collect;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Warehouses", inversedBy="inventories")
     * @ORM\JoinColumn(nullable=false)
     */
    private $warehouse;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Delivery", inversedBy="inventory")
     */
    private $delivery;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateExpire(): ?\DateTimeInterface
    {
        return $this->dateExpire;
    }

    public function setDateExpire(\DateTimeInterface $dateExpire): self
    {
        $this->dateExpire = $dateExpire;

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

    public function getCommentary(): ?string
    {
        return $this->commentary;
    }

    public function setCommentary(string $commentary): self
    {
        $this->commentary = $commentary;

        return $this;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(int $number): self
    {
        $this->number = $number;

        return $this;
    }

    public function getWeight(): ?float
    {
        return $this->weight;
    }

    public function setWeight(float $weight): self
    {
        $this->weight = $weight;

        return $this;
    }

    public function getFoods(): ?Foods
    {
        return $this->foods;
    }

    public function setFoods(?Foods $foods): self
    {
        $this->foods = $foods;

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

    public function getStatus(): ?Status
    {
        return $this->status;
    }

    public function setStatus(?Status $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getCollect(): ?Collect
    {
        return $this->collect;
    }

    public function setCollect(?Collect $collect): self
    {
        $this->collect = $collect;

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

    public function getDelivery(): ?Delivery
    {
        return $this->delivery;
    }

    public function setDelivery(?Delivery $delivery): self
    {
        $this->delivery = $delivery;

        return $this;
    }
}
