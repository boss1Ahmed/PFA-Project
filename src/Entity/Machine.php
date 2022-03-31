<?php

namespace App\Entity;

use App\Repository\MachineRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MachineRepository::class)
 */
class Machine
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
    private $nom_machine;

    /**
     * @ORM\Column(type="date")
     */
    private $date_install;

    /**
     * @ORM\OneToMany(targetEntity=Intervention::class, mappedBy="machine")
     */
    private $interventions;

    /**
     * @ORM\ManyToMany(targetEntity=Defaillance::class, inversedBy="machines")
     */
    private $defaillances;

    public function __construct()
    {
        $this->interventions = new ArrayCollection();
        $this->defaillances = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomMachine(): ?string
    {
        return $this->nom_machine;
    }

    public function setNomMachine(string $nom_machine): self
    {
        $this->nom_machine = $nom_machine;

        return $this;
    }

    public function getDateInstall(): ?\DateTimeInterface
    {
        return $this->date_install;
    }

    public function setDateInstall(\DateTimeInterface $date_install): self
    {
        $this->date_install = $date_install;

        return $this;
    }

    /**
     * @return Collection<int, Intervention>
     */
    public function getInterventions(): Collection
    {
        return $this->interventions;
    }

    public function addIntervention(Intervention $intervention): self
    {
        if (!$this->interventions->contains($intervention)) {
            $this->interventions[] = $intervention;
            $intervention->setMachine($this);
        }

        return $this;
    }

    public function removeIntervention(Intervention $intervention): self
    {
        if ($this->interventions->removeElement($intervention)) {
            // set the owning side to null (unless already changed)
            if ($intervention->getMachine() === $this) {
                $intervention->setMachine(null);
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
        }

        return $this;
    }

    public function removeDefaillance(Defaillance $defaillance): self
    {
        $this->defaillances->removeElement($defaillance);

        return $this;
    }
}
