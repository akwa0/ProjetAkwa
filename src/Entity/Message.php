<?php

namespace App\Entity;

use App\Repository\MessageRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MessageRepository::class)]
class Message
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $contenu = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTime $dateEnvoie = null;

    #[ORM\Column]
    private ?bool $Etat = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(?string $contenu): static
    {
        $this->contenu = $contenu;

        return $this;
    }

    public function getDateEnvoie(): ?\DateTime
    {
        return $this->dateEnvoie;
    }

    public function setDateEnvoie(?\DateTime $dateEnvoie): static
    {
        $this->dateEnvoie = $dateEnvoie;

        return $this;
    }

    public function isEtat(): ?bool
    {
        return $this->Etat;
    }

    public function setEtat(bool $Etat): static
    {
        $this->Etat = $Etat;

        return $this;
    }
}
