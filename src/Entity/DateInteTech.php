<?php

namespace App\Entity;

use App\Repository\DateInteTechRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DateInteTechRepository::class)
 */
class DateInteTech
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime", nullable="true")
     *
     */
    private $dateDebut;

    /**
     * @ORM\Column(type="datetime",nullable="true")
     *
     */
    private $dateFin;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="dateInteTeches")
     */
    private $technicien;

    /**
     * @ORM\ManyToOne(targetEntity=Intervention::class, inversedBy="dateInteTeches")
     */
    private $intervention;

    /**
     * @ORM\OneToMany(targetEntity=PiecesofTache::class, mappedBy="tache")
     */
    private $pieces;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $notified;

    public function __construct()
    {
        $this->pieces = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(\DateTimeInterface $dateDebut): self
    {
        $this->dateDebut = $dateDebut;

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

    public function getTechnicien(): ?User
    {
        return $this->technicien;
    }

    public function setTechnicien(?User $technicien): self
    {
        $this->technicien = $technicien;

        return $this;
    }

    public function getIntervention(): ?Intervention
    {
        return $this->intervention;
    }

    public function setIntervention(?Intervention $intervention): self
    {
        $this->intervention = $intervention;

        return $this;
    }

    /**
     * @return Collection<int, PiecesofTache>
     */
    public function getPieces(): Collection
    {
        return $this->pieces;
    }

    public function addPiece(PiecesofTache $piece): self
    {
        if (!$this->pieces->contains($piece)) {
            $this->pieces[] = $piece;
            $piece->setTache($this);
        }

        return $this;
    }

    public function removePiece(PiecesofTache $piece): self
    {
        if ($this->pieces->removeElement($piece)) {
            // set the owning side to null (unless already changed)
            if ($piece->getTache() === $this) {
                $piece->setTache(null);
            }
        }

        return $this;
    }

    public function getNotified(): ?bool
    {
        return $this->notified;
    }

    public function setNotified(?bool $notified): self
    {
        $this->notified = $notified;

        return $this;
    }
}
