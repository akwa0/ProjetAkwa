<?php

namespace App\Entity;

use App\Repository\AvisRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AvisRepository::class)]
class Avis
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $nombreEtoile = null;

    #[ORM\Column(nullable: true)]
    private ?int $nombreCommentaire = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $dateCreation = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $commentaire = null;

    #[ORM\ManyToOne(inversedBy: 'avis')]
    private ?Utilisateur $utilisateurs = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombreEtoile(): ?int
    {
        return $this->nombreEtoile;
    }

    public function setNombreEtoile(?int $nombreEtoile): static
    {
        $this->nombreEtoile = $nombreEtoile;

        return $this;
    }

    public function getNombreCommentaire(): ?int
    {
        return $this->nombreCommentaire;
    }

    public function setNombreCommentaire(?int $nombreCommentaire): static
    {
        $this->nombreCommentaire = $nombreCommentaire;

        return $this;
    }

    public function getDateCreation(): ?\DateTime
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTime $dateCreation): static
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(?string $commentaire): static
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    public function getUtilisateurs(): ?Utilisateur
    {
        return $this->utilisateurs;
    }

    public function setUtilisateurs(?Utilisateur $utilisateurs): static
    {
        $this->utilisateurs = $utilisateurs;

        return $this;
    }
}
