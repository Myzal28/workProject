<?php
namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity(repositoryClass="App\Repository\IndividualOfferRepository")
 */
class IndividualOffer implements \JsonSerializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\Column(type="string", length=120)
     */
    private $name;
    /**
     * @ORM\Column(type="text")
     */
    private $description;
    /**
     * @ORM\Column(type="datetime")
     */
    private $dateCreate;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Persons", inversedBy="individualOffer")
     * @ORM\JoinColumn(nullable=false)
     */
    private $personCreate;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $contact;

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
    public function getDescription(): ?string
    {
        return $this->description;
    }
    public function setDescription(?string $description): self
    {
        $this->description = $description;
        return $this;
    }
    public function getDateCreate(): ?\DateTimeInterface
    {
        return $this->dateCreate;
    }
    public function setDateCreate(\DateTimeInterface $dateCreate): self
    {
        $this->dateCreate = $dateCreate;
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

    public function getContact(): ?string
    {
        return $this->contact;
    }

    public function setContact(string $contact): self
    {
        $this->contact = $contact;

        return $this;
    }

    /**
     * Specify data which should be serialized to JSON
     * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}