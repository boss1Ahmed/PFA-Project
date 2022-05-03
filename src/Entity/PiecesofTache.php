<?php

namespace App\Entity;

use App\Repository\PiecesofTacheRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PiecesofTacheRepository::class)
 */
class PiecesofTache
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $quantite;

    /**
     * @ORM\ManyToOne(targetEntity=PieceRechange::class, inversedBy="taches")
     */
    private $pieceRechange;

    /**
     * @ORM\ManyToOne(targetEntity=DateInteTech::class, inversedBy="pieces")
     */
    private $tache;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(?int $quantite): self
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getPieceRechange(): ?PieceRechange
    {
        return $this->pieceRechange;
    }

    public function setPieceRechange(?PieceRechange $pieceRechange): self
    {
        $this->pieceRechange = $pieceRechange;

        return $this;
    }

    public function getTache(): ?DateInteTech
    {
        return $this->tache;
    }

    public function setTache(?DateInteTech $tache): self
    {
        $this->tache = $tache;

        return $this;
    }
}
