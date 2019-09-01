<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GuardingRepository")
 */
class Guarding implements \JsonSerializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Persons", inversedBy="toGuard")
     * @ORM\JoinColumn(nullable=false)
     */
    private $userToGuard;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Persons", inversedBy="guardings")
     */
    private $userGuarding;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getUserToGuard(): ?Persons
    {
        return $this->userToGuard;
    }

    public function setUserToGuard(?Persons $userToGuard): self
    {
        $this->userToGuard = $userToGuard;

        return $this;
    }

    public function getUserGuarding(): ?Persons
    {
        return $this->userGuarding;
    }

    public function setUserGuarding(?Persons $userGuarding): self
    {
        $this->userGuarding = $userGuarding;

        return $this;
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}
