<?php

namespace App\Entity;

use App\Repository\UserRepository;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;


/**
* @ORM\Entity(repositoryClass=UserRepository::class)
 * @Vich\Uploadable
*/
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255 , nullable=true)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255 , nullable=true)
     */
    private $prenom;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $valable;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $taux_horaire;

    /**
     * @ORM\ManyToOne(targetEntity=TypeDefaillance::class, inversedBy="techniciens")
     */
    private $typeTech;

    /**
     * @ORM\OneToMany(targetEntity=Intervention::class, mappedBy="conducteur")
     */
    private $intersConducteur;

    /**
     * @ORM\OneToMany(targetEntity=DateInteTech::class, mappedBy="technicien")
     */
    private $dateInteTeches;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $poste;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $addresse;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $tele;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $dateDeNaissance;

    /**
     * @Vich\UploadableField(mapping="photos",fileNameProperty="imageName")
     * @var File|null
     */
    private $imageFile;

    /**
     * @ORM\Column(type="string", length=255)
     * @var string|null
     */
    private $imageName;

    /**
     * @ORM\Column(type="datetime",nullable=true)
     */
    private $updatedAt;

    public function __construct()
    {
        parent::__construct();
        $this->interventions = new ArrayCollection();
        $this->intersConducteur = new ArrayCollection();
        $this->dateInteTeches = new ArrayCollection();
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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getValable(): ?bool
    {
        return $this->valable;
    }

    public function setValable(?bool $valable): self
    {
        $this->valable = $valable;

        return $this;
    }

    public function getTauxHoraire(): ?float
    {
        return $this->taux_horaire;
    }

    public function setTauxHoraire(?float $taux_horaire): self
    {
        $this->taux_horaire = $taux_horaire;

        return $this;
    }

    public function getTypeTech(): ?TypeDefaillance
    {
        return $this->typeTech;
    }

    public function setTypeTech(?TypeDefaillance $typeTech): self
    {
        $this->typeTech = $typeTech;

        return $this;
    }

    /**
     * @return Collection<int, Intervention>
     */
    public function getIntersConducteur(): Collection
    {
        return $this->intersConducteur;
    }

    public function addIntersConducteur(Intervention $intersConducteur): self
    {
        if (!$this->intersConducteur->contains($intersConducteur)) {
            $this->intersConducteur[] = $intersConducteur;
            $intersConducteur->setConducteur($this);
        }

        return $this;
    }

    public function removeIntersConducteur(Intervention $intersConducteur): self
    {
        if ($this->intersConducteur->removeElement($intersConducteur)) {
            // set the owning side to null (unless already changed)
            if ($intersConducteur->getConducteur() === $this) {
                $intersConducteur->setConducteur(null);
            }
        }

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
            $dateInteTech->setTechnicien($this);
        }

        return $this;
    }

    public function removeDateInteTech(DateInteTech $dateInteTech): self
    {
        if ($this->dateInteTeches->removeElement($dateInteTech)) {
            // set the owning side to null (unless already changed)
            if ($dateInteTech->getTechnicien() === $this) {
                $dateInteTech->setTechnicien(null);
            }
        }

        return $this;
    }

    public function getPoste(): ?string
    {
        return $this->poste;
    }

    public function setPoste(?string $poste): self
    {
        $this->poste = $poste;

        return $this;
    }

    public function getAddresse(): ?string
    {
        return $this->addresse;
    }

    public function setAddresse(?string $addresse): self
    {
        $this->addresse = $addresse;

        return $this;
    }

    public function getTele(): ?string
    {
        return $this->tele;
    }

    public function setTele(?string $tele): self
    {
        $this->tele = $tele;

        return $this;
    }

    public function getDateDeNaissance(): ?\DateTimeInterface
    {
        return $this->dateDeNaissance;
    }

    public function setDateDeNaissance(?\DateTimeInterface $dateDeNaissance): self
    {
        $this->dateDeNaissance = $dateDeNaissance;

        return $this;
    }

    /**
     * @return File|null
     */
    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    /**
     * @param File|UploadedFile|null $imageFile
     */
    public function setImageFile(?File $imageFile): void
    {
        $this->imageFile = $imageFile;
        if ($imageFile) {
            // if 'updatedAt' is not defined in your entity, use another property
            $this->updatedAt = new \DateTime('now');
        }
    }

    /**
     * @return string|null
     */
    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    /**
     * @param string|null $imageName
     */
    public function setImageName(?string $imageName): void
    {
        $this->imageName = $imageName;

    }

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTime $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }


}
