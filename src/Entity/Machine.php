<?php

namespace App\Entity;

use App\Repository\MachineRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;

/**
 * @ORM\Entity(repositoryClass=MachineRepository::class)
 * @Vich\Uploadable
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

    /**
     * @Vich\UploadableField(mapping="documentation",fileNameProperty="documentName")
     * @var File|null
     */
    private $documentFile;

    /**
     * @ORM\Column(type="string", length=255)
     * @var string|null
     */
    private $documentName;

    /**
     * @ORM\OneToMany(targetEntity=Zone::class, mappedBy="machine")
     */
    private $zones;

    public function __construct()
    {
        $this->interventions = new ArrayCollection();
        $this->defaillances = new ArrayCollection();
        $this->zones = new ArrayCollection();
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


    public function getDocumentFile(): ?File
    {
        return $this->documentFile;
    }

    /**
     *  @param File|UploadedFile|null $documentFile
     */
    public function setDocumentFile(?File $documentFile): void
    {
        $this->documentFile = $documentFile;
    }

    /**
     * @return string|null
     */
    public function getDocumentName(): ?string
    {
        return $this->documentName;
    }

    /**
     * @param string|null $documentName
     */
    public function setDocumentName(?string $documentName): void
    {
        $this->documentName = $documentName;
    }

    /**
     * @return Collection<int, Zone>
     */
    public function getZones(): Collection
    {
        return $this->zones;
    }

    public function addZone(Zone $zone): self
    {
        if (!$this->zones->contains($zone)) {
            $this->zones[] = $zone;
            $zone->setMachine($this);
        }

        return $this;
    }

    public function removeZone(Zone $zone): self
    {
        if ($this->zones->removeElement($zone)) {
            // set the owning side to null (unless already changed)
            if ($zone->getMachine() === $this) {
                $zone->setMachine(null);
            }
        }

        return $this;
    }


}
