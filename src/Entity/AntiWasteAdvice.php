<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AntiWasteAdviceRepository")
 */
class AntiWasteAdvice
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
    private $advice;

    /**
     * @ORM\Column(type="date")
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Persons", inversedBy="antiWasteAdvices")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Persons", inversedBy="upvotedWasteAdvices")
     */
    private $upvoted;

    public function __construct()
    {
        $this->upvoted = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAdvice(): ?string
    {
        return $this->advice;
    }

    public function setAdvice(string $advice): self
    {
        $this->advice = $advice;

        return $this;
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

    public function getUser(): ?Persons
    {
        return $this->user;
    }

    public function setUser(?Persons $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection|Persons[]
     */
    public function getUpvoted(): Collection
    {
        return $this->upvoted;
    }

    public function addUpvoted(Persons $upvoted): self
    {
        if (!$this->upvoted->contains($upvoted)) {
            $this->upvoted[] = $upvoted;
        }

        return $this;
    }

    public function removeUpvoted(Persons $upvoted): self
    {
        if ($this->upvoted->contains($upvoted)) {
            $this->upvoted->removeElement($upvoted);
        }

        return $this;
    }
}
