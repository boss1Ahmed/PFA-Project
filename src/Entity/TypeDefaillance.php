<?php

namespace App\Entity;

use App\Repository\TypeDefaillanceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TypeDefaillanceRepository::class)
 */
class TypeDefaillance
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="typeTech")
     */
    private $users;

    /**
     * @ORM\OneToMany(targetEntity=Defaillance::class, mappedBy="typeDefaillance")
     */
    private $defaillances;


    public function __construct()
    {
        $this->techniciens = new ArrayCollection();
        $this->users = new ArrayCollection();
        $this->defaillances = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setTypeTech($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getTypeTech() === $this) {
                $user->setTypeTech(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Defaillance>
     */
    public function getDefaillances(): Collection
    {
        return $this->defaillances;
    }

    public function addDefaillance(Defaillance $defaillance): self
    {
        if (!$this->defaillances->contains($defaillance)) {
            $this->defaillances[] = $defaillance;
            $defaillance->setTypeDefaillance($this);
        }

        return $this;
    }

    public function removeDefaillance(Defaillance $defaillance): self
    {
        if ($this->defaillances->removeElement($defaillance)) {
            // set the owning side to null (unless already changed)
            if ($defaillance->getTypeDefaillance() === $this) {
                $defaillance->setTypeDefaillance(null);
            }
        }

        return $this;
    }



}
