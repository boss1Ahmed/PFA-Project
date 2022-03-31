<?php

namespace App\Entity;

use App\Repository\PieceRechangeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PieceRechangeRepository::class)
 */
class PieceRechange
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
     * @ORM\ManyToMany(targetEntity=Intervention::class, mappedBy="piecesRechange")
     */
    private $interventions;

    /**
     * @ORM\ManyToMany(targetEntity=Defaillance::class, mappedBy="piecesRechange")
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
            $intervention->addPiecesRechange($this);
        }

        return $this;
    }

    public function removeIntervention(Intervention $intervention): self
    {
        if ($this->interventions->removeElement($intervention)) {
            $intervention->removePiecesRechange($this);
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
            $defaillance->addPiecesRechange($this);
        }

        return $this;
    }

    public function removeDefaillance(Defaillance $defaillance): self
    {
        if ($this->defaillances->removeElement($defaillance)) {
            $defaillance->removePiecesRechange($this);
        }

        return $this;
    }
}
