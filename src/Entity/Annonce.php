<?php

namespace App\Entity;

use App\Repository\AnnonceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AnnonceRepository::class)]
class Annonce
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $title;

    #[ORM\Column(type: 'text')]
    private $description;

    #[ORM\Column(type: 'integer')]
    private $salaire;

    #[ORM\Column(type: 'boolean')]
    private $valide = false;



    #[ORM\OneToMany(mappedBy: 'annonce_id', targetEntity: Candidature::class)]
    private $candidatures;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'annonces')]
    private $auteur;

    #[ORM\OneToMany(mappedBy: 'annonce', targetEntity: Candidatures::class, orphanRemoval: true)]
    private $candidaturess;

    public function __construct()
    {
        $this->candidatures = new ArrayCollection();
        // $this->valide = false;
        $this->candidaturess = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getSalaire(): ?int
    {
        return $this->salaire;
    }

    public function setSalaire(int $salaire): self
    {
        $this->salaire = $salaire;

        return $this;
    }

    public function isValide(): ?bool
    {
        return $this->valide;
    }

    public function setValide(bool $valide): self
    {
        $this->valide = $valide;

        return $this;
    }

    /**
     * @return Collection<int, Candidature>
     */
    public function getCandidatures(): Collection
    {
        return $this->candidatures;
    }

    public function addCandidature(Candidature $candidature): self
    {
        if (!$this->candidatures->contains($candidature)) {
            $this->candidatures[] = $candidature;
            $candidature->setAnnonceId($this);
        }

        return $this;
    }

    public function removeCandidature(Candidature $candidature): self
    {
        if ($this->candidatures->removeElement($candidature)) {
            // set the owning side to null (unless already changed)
            if ($candidature->getAnnonceId() === $this) {
                $candidature->setAnnonceId(null);
            }
        }

        return $this;
    }

   

    public function getAuteur(): ?User
    {
        return $this->auteur;
    }

    public function setAuteur(?User $auteur): self
    {
        $this->auteur = $auteur;

        return $this;
    }

    public function __toString()
    {
        return $this->title;
    }

    /**
     * @return Collection<int, Candidatures>
     */
    public function getCandidaturess(): Collection
    {
        return $this->candidaturess;
    }

    public function addCandidaturess(Candidatures $candidaturess): self
    {
        if (!$this->candidaturess->contains($candidaturess)) {
            $this->candidaturess[] = $candidaturess;
            $candidaturess->setAnnonce($this);
        }

        return $this;
    }

    public function removeCandidaturess(Candidatures $candidaturess): self
    {
        if ($this->candidaturess->removeElement($candidaturess)) {
            // set the owning side to null (unless already changed)
            if ($candidaturess->getAnnonce() === $this) {
                $candidaturess->setAnnonce(null);
            }
        }

        return $this;
    }
}
