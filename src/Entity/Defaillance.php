<?php

namespace App\Entity;

use App\Repository\DefaillanceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DefaillanceRepository::class)
 */
class Defaillance
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
    private $libelle;

    /**
     * @ORM\ManyToMany(targetEntity=Machine::class, mappedBy="defaillances")
     */
    private $machines;

    /**
     * @ORM\OneToMany(targetEntity=Intervention::class, mappedBy="defaillance")
     */
    private $interventions;

    /**
     * @ORM\ManyToOne(targetEntity=TypeDefaillance::class, inversedBy="defaillances")
     */
    private $typeDefaillance;

    /**
     * @ORM\ManyToMany(targetEntity=PieceRechange::class, inversedBy="defaillances")
     */
    private $piecesRechange;

    public function __construct()
    {
        $this->machines = new ArrayCollection();
        $this->interventions = new ArrayCollection();
        $this->piecesRechange = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * @return Collection<int, Machine>
     */
    public function getMachines(): Collection
    {
        return $this->machines;
    }

    public function addMachine(Machine $machine): self
    {
        if (!$this->machines->contains($machine)) {
            $this->machines[] = $machine;
            $machine->addDefaillance($this);
        }

        return $this;
    }

    public function removeMachine(Machine $machine): self
    {
        if ($this->machines->removeElement($machine)) {
            $machine->removeDefaillance($this);
        }

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
            $intervention->setDefaillance($this);
        }

        return $this;
    }

    public function removeIntervention(Intervention $intervention): self
    {
        if ($this->interventions->removeElement($intervention)) {
            // set the owning side to null (unless already changed)
            if ($intervention->getDefaillance() === $this) {
                $intervention->setDefaillance(null);
            }
        }

        return $this;
    }

    public function getTypeDefaillance(): ?TypeDefaillance
    {
        return $this->typeDefaillance;
    }

    public function setTypeDefaillance(?TypeDefaillance $typeDefaillance): self
    {
        $this->typeDefaillance = $typeDefaillance;

        return $this;
    }

    /**
     * @return Collection<int, PieceRechange>
     */
    public function getPiecesRechange(): Collection
    {
        return $this->piecesRechange;
    }

    public function addPiecesRechange(PieceRechange $piecesRechange): self
    {
        if (!$this->piecesRechange->contains($piecesRechange)) {
            $this->piecesRechange[] = $piecesRechange;
        }

        return $this;
    }

    public function removePiecesRechange(PieceRechange $piecesRechange): self
    {
        $this->piecesRechange->removeElement($piecesRechange);

        return $this;
    }
}
