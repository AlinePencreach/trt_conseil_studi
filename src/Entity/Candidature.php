<?php

namespace App\Entity;

use App\Repository\CandidatureRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CandidatureRepository::class)]
class Candidature
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'candidatures')]
    private $candidat_id;

    #[ORM\ManyToOne(targetEntity: Annonce::class, inversedBy: 'candidatures')]
    private $annonce_id;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCandidatId(): ?User
    {
        return $this->candidat_id;
    }

    public function setCandidatId(?User $candidat_id): self
    {
        $this->candidat_id = $candidat_id;

        return $this;
    }

    public function getAnnonceId(): ?Annonce
    {
        return $this->annonce_id;
    }

    public function setAnnonceId(?Annonce $annonce_id): self
    {
        $this->annonce_id = $annonce_id;

        return $this;
    }

    public function __toString()
    {
        return $this->candidatures;
    }
}
