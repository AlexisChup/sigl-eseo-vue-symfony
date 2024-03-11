<?php

namespace App\Entity;

use App\Repository\JuryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;

#[ORM\Entity(repositoryClass: JuryRepository::class)]
class Jury
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Event $evenement = null;

    #[ORM\ManyToMany(targetEntity: Utilisateur::class)]
    private Collection $auditeur;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Apprenti $apprenti = null;

    public function __construct()
    {
        $this->auditeur = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEvenement(): ?Event
    {
        return $this->evenement;
    }

    public function setEvenement(?Event $evenement): self
    {
        $this->evenement = $evenement;

        return $this;
    }

    /**
     * @return Collection<int, Utilisateur>
     */
    public function getAuditeur(): Collection
    {
        return $this->auditeur;
    }

    public function addAuditeur(Utilisateur $auditeur): self
    {
        if (!$this->auditeur->contains($auditeur)) {
            $this->auditeur->add($auditeur);
        }

        return $this;
    }

    public function removeAudience(Utilisateur $auditeur): self
    {
        $this->auditeur->removeElement($auditeur);

        return $this;
    }

    public function clearAudience(): self
    {
        $this->auditeur = new ArrayCollection();

        return $this;
    }

    public function getApprenti(): ?Apprenti
    {
        return $this->apprenti;
    }

    public function setApprenti(Apprenti $apprenti): self
    {
        $this->apprenti = $apprenti;

        return $this;
    }
}
