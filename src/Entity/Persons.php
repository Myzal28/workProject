<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PersonsRepository")
 * @UniqueEntity(
 *  fields={"email"},
 *  message= "L'email que vous avez choisi, existe déjà !"
 * )
 */
class Persons implements UserInterface
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
    private $lastname;

    /**
     * @ORM\Column(type="string", length=120)
     */
    private $firstname;

    /**
     * @ORM\Column(type="date")
     */
    private $birthday;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\EqualTo(propertyPath="confirm_password", message="Passwords Don't match !")
     */
    private $password;

    public $confirm_password;

    public $type_choice;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=15)
     */
    private $phoneNbr;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Email()
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=120)
     */
    private $country;

    /**
     * @ORM\Column(type="string", length=60)
     */
    private $zipcode;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateRegister;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateModify;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $company;

    /**
     * @ORM\Column(type="smallint")
     */
    private $adminSite;

    /**
     * @ORM\Column(type="smallint")
     */
    private $volunteer;

    /**
     * @ORM\Column(type="smallint")
     */
    private $internal;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Services", inversedBy="persons")
     */
    private $service;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Warehouses", inversedBy="persons")
     */
    private $warehouse;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Vehicles", mappedBy="person")
     */
    private $vehicles;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Collect", mappedBy="personCheck")
     */
    private $collectsCheck;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Collect", mappedBy="personCreate", orphanRemoval=true)
     */
    private $collectsCreate;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Inventory", mappedBy="personCreate", orphanRemoval=true)
     */
    private $inventories;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Signup", mappedBy="person", cascade={"persist", "remove"})
     */
    private $signup;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Calendar", mappedBy="persons")
     */
    private $calendars;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Articles", mappedBy="personCreate", orphanRemoval=true)
     */
    private $articles;

    /**
     * @ORM\Column(type="integer")
     */
    private $ClientPar;

    /**
     * @ORM\Column(type="integer")
     */
    private $ClientPro;

    public function __construct()
    {
        $this->vehicles = new ArrayCollection();
        $this->collectsCheck = new ArrayCollection();
        $this->collectsCreate = new ArrayCollection();
        $this->inventories = new ArrayCollection();
        $this->calendars = new ArrayCollection();
        $this->articles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getBirthday(): ?\DateTimeInterface
    {
        return $this->birthday;
    }

    public function setBirthday(\DateTimeInterface $birthday): self
    {
        $this->birthday = $birthday;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

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

    public function getPhoneNbr(): ?string
    {
        return $this->phoneNbr;
    }

    public function setPhoneNbr(string $phoneNbr): self
    {
        $this->phoneNbr = $phoneNbr;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

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

    public function getDateRegister(): ?\DateTimeInterface
    {
        return $this->dateRegister;
    }

    public function setDateRegister(\DateTimeInterface $dateRegister): self
    {
        $this->dateRegister = $dateRegister;

        return $this;
    }

    public function getDateModify(): ?\DateTimeInterface
    {
        return $this->dateModify;
    }

    public function setDateModify(?\DateTimeInterface $dateModify): self
    {
        $this->dateModify = $dateModify;

        return $this;
    }

    public function getCompany(): ?string
    {
        return $this->company;
    }

    public function setCompany(?string $company): self
    {
        $this->company = $company;

        return $this;
    }

    public function getAdminSite(): ?int
    {
        return $this->adminSite;
    }

    public function setAdminSite(int $adminSite): self
    {
        $this->adminSite = $adminSite;

        return $this;
    }

    public function getVolunteer(): ?int
    {
        return $this->volunteer;
    }

    public function setVolunteer(int $volunteer): self
    {
        $this->volunteer = $volunteer;

        return $this;
    }

    public function getInternal(): ?int
    {
        return $this->internal;
    }

    public function setInternal(int $internal): self
    {
        $this->internal = $internal;

        return $this;
    }

    public function getService(): ?Services
    {
        return $this->service;
    }

    public function setService(?Services $service): self
    {
        $this->service = $service;

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
            $vehicle->setPerson($this);
        }

        return $this;
    }

    public function removeVehicle(Vehicles $vehicle): self
    {
        if ($this->vehicles->contains($vehicle)) {
            $this->vehicles->removeElement($vehicle);
            // set the owning side to null (unless already changed)
            if ($vehicle->getPerson() === $this) {
                $vehicle->setPerson(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Collect[]
     */
    public function getCollectsCheck(): Collection
    {
        return $this->collectsCheck;
    }

    public function addCollectsCheck(Collect $collectsCheck): self
    {
        if (!$this->collectsCheck->contains($collectsCheck)) {
            $this->collectsCheck[] = $collectsCheck;
            $collectsCheck->setPersonCheck($this);
        }

        return $this;
    }

    public function removeCollectsCheck(Collect $collectsCheck): self
    {
        if ($this->collectsCheck->contains($collectsCheck)) {
            $this->collectsCheck->removeElement($collectsCheck);
            // set the owning side to null (unless already changed)
            if ($collectsCheck->getPersonCheck() === $this) {
                $collectsCheck->setPersonCheck(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Collect[]
     */
    public function getCollectsCreate(): Collection
    {
        return $this->collectsCreate;
    }

    public function addCollectsCreate(Collect $collectsCreate): self
    {
        if (!$this->collectsCreate->contains($collectsCreate)) {
            $this->collectsCreate[] = $collectsCreate;
            $collectsCreate->setPersonCreate($this);
        }

        return $this;
    }

    public function removeCollectsCreate(Collect $collectsCreate): self
    {
        if ($this->collectsCreate->contains($collectsCreate)) {
            $this->collectsCreate->removeElement($collectsCreate);
            // set the owning side to null (unless already changed)
            if ($collectsCreate->getPersonCreate() === $this) {
                $collectsCreate->setPersonCreate(null);
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
            $inventory->setPersonCreate($this);
        }

        return $this;
    }

    public function removeInventory(Inventory $inventory): self
    {
        if ($this->inventories->contains($inventory)) {
            $this->inventories->removeElement($inventory);
            // set the owning side to null (unless already changed)
            if ($inventory->getPersonCreate() === $this) {
                $inventory->setPersonCreate(null);
            }
        }

        return $this;
    }

    public function getSignup(): ?Signup
    {
        return $this->signup;
    }

    public function setSignup(Signup $signup): self
    {
        $this->signup = $signup;

        // set the owning side of the relation if necessary
        if ($this !== $signup->getPerson()) {
            $signup->setPerson($this);
        }

        return $this;
    }

    /**
     * @return Collection|Calendar[]
     */
    public function getCalendars(): Collection
    {
        return $this->calendars;
    }

    public function addCalendar(Calendar $calendar): self
    {
        if (!$this->calendars->contains($calendar)) {
            $this->calendars[] = $calendar;
            $calendar->addPerson($this);
        }

        return $this;
    }

    public function removeCalendar(Calendar $calendar): self
    {
        if ($this->calendars->contains($calendar)) {
            $this->calendars->removeElement($calendar);
            $calendar->removePerson($this);
        }

        return $this;
    }

    /**
     * @return Collection|Articles[]
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    public function addArticle(Articles $article): self
    {
        if (!$this->articles->contains($article)) {
            $this->articles[] = $article;
            $article->setPersonCreate($this);
        }

        return $this;
    }

    public function removeArticle(Articles $article): self
    {
        if ($this->articles->contains($article)) {
            $this->articles->removeElement($article);
            // set the owning side to null (unless already changed)
            if ($article->getPersonCreate() === $this) {
                $article->setPersonCreate(null);
            }
        }

        return $this;
    }

    public function eraseCredentials() {}
    
    public function getSalt() {}

    public function getUsername() {
        return $this->getFirstname();
    }
    
    public function getRoles() {
        if($this->getAdminSite() == 1){
            return ['ROLE_ADMIN'];
        }elseif($this->getVolunteer() == 1){
            return ['ROLE_VOL'];
        }
        return ['ROLE_CLI'];
    }

    public function getClientPar(): ?int
    {
        return $this->ClientPar;
    }

    public function setClientPar(int $ClientPar): self
    {
        $this->ClientPar = $ClientPar;

        return $this;
    }

    public function getClientPro(): ?int
    {
        return $this->ClientPro;
    }

    public function setClientPro(int $ClientPro): self
    {
        $this->ClientPro = $ClientPro;

        return $this;
    }

}


