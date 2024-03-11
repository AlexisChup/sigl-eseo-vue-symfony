<?php

namespace App\Entity;

use App\Repository\ApprentiRepository;
use App\Repository\UtilisateurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ApprentiRepository::class)]
class Apprenti
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(onDelete: "CASCADE")]
    private ?Utilisateur $utilisateur = null;

    #[ORM\ManyToOne(inversedBy: 'apprentis')]
    #[ORM\JoinColumn(nullable: true, onDelete: "SET NULL")]
    private ?Enseignant $enseignant = null;

    #[ORM\ManyToOne(inversedBy: 'apprentis')]
    #[ORM\JoinColumn(nullable: true, onDelete: "SET NULL")]
    private ?MaitreApprentissage $maitreApprentissage = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $mission = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: true)]
    private ?Promotion $idPromotion = null;

    #[ORM\Column(nullable: true)]
    private ?bool $missionValide = null;


    public function __construct()
    {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUtilisateur(): ?Utilisateur
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(Utilisateur $utilisateur): self
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }

    public function getEnseignant(): ?Enseignant
    {
        return $this->enseignant;
    }

    public function setEnseignant(?Enseignant $enseignant): self
    {
        $this->enseignant = $enseignant;

        return $this;
    }

    public function getMaitreApprentissage(): ?MaitreApprentissage
    {
        return $this->maitreApprentissage;
    }

    public function setMaitreApprentissage(?MaitreApprentissage $maitreApprentissage): self
    {
        $this->maitreApprentissage = $maitreApprentissage;

        return $this;
    }

    public function getMission(): ?string
    {
        return $this->mission;
    }

    public function setMission(?string $mission): self
    {
        $this->mission = $mission;

        return $this;
    }

    public function getIdPromotion(): ?Promotion
    {
        return $this->idPromotion;
    }

    public function setIdPromotion(?Promotion $idPromotion): self
    {
        $this->idPromotion = $idPromotion;

        return $this;
    }

    public function isMissionValide(): ?bool
    {
        return $this->missionValide;
    }

    public function setMissionValide(?bool $missionValide): self
    {
        $this->missionValide = $missionValide;

        return $this;
    }
}
