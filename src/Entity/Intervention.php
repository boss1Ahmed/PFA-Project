<?php

namespace App\Entity;

use App\Repository\InterventionRepository;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=InterventionRepository::class)
 */
class Intervention
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateLancement;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateFin;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $urgence;


    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="intersConducteur")
     * @ORM\JoinColumn(nullable=false)
     */
    private $conducteur;

    /**
     * @ORM\OneToMany(targetEntity=DateInteTech::class, mappedBy="intervention")
     */
    private $dateInteTeches;

    /**
     * @ORM\ManyToOne(targetEntity=Defaillance::class, inversedBy="interventions")
     */
    private $defaillance;

    /**
     * @ORM\ManyToMany(targetEntity=PieceRechange::class, inversedBy="interventions")
     */
    private $piecesRechange;

    /**
     * @ORM\ManyToOne(targetEntity=Machine::class, inversedBy="interventions")
     */
    private $machine;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $etat;

    public function __construct()
    {

        $this->dateInteTeches = new ArrayCollection();
        $this->piecesRechange = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateLancement(): ?\DateTimeInterface
    {
        return $this->dateLancement;
    }

    public function setDateLancement(\DateTimeInterface $dateLancement): self
    {
        $this->dateLancement = $dateLancement;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->dateFin;
    }

    public function setDateFin(?\DateTimeInterface $dateFin): self
    {
        $this->dateFin = $dateFin;

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

    public function getUrgence(): ?bool
    {
        return $this->urgence;
    }

    public function setUrgence(?bool $urgence): self
    {
        $this->urgence = $urgence;

        return $this;
    }


    /**
     * @return Collection<int, User>
     */
    public function getConducteur(): ?User
    {
        return $this->conducteur;
    }

    public function setConducteur(?User $conducteur): self
    {
        $this->conducteur = $conducteur;

        return $this;
    }

    /**
     * @return Collection<int, DateInteTech>
     */
    public function getDateInteTeches(): Collection
    {
        return $this->dateInteTeches;
    }

    public function addDateInteTech(DateInteTech $dateInteTech): self
    {
        if (!$this->dateInteTeches->contains($dateInteTech)) {
            $this->dateInteTeches[] = $dateInteTech;
            $dateInteTech->setIntervention($this);
        }

        return $this;
    }

    public function removeDateInteTech(DateInteTech $dateInteTech): self
    {
        if ($this->dateInteTeches->removeElement($dateInteTech)) {
            // set the owning side to null (unless already changed)
            if ($dateInteTech->getIntervention() === $this) {
                $dateInteTech->setIntervention(null);
            }
        }

        return $this;
    }

    public function getDefaillance(): ?Defaillance
    {
        return $this->defaillance;
    }

    public function setDefaillance(?Defaillance $defaillance): self
    {
        $this->defaillance = $defaillance;

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

    public function getMachine(): ?Machine
    {
        return $this->machine;
    }

    public function setMachine(?Machine $machine): self
    {
        $this->machine = $machine;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(?string $etat): self
    {
        $this->etat = $etat;

        return $this;
    }
}
