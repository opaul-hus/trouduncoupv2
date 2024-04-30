<?php

namespace App\Entity;

use App\Repository\CommandeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommandeRepository::class)]
class Commande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateCommande = null;

    #[ORM\ManyToOne(inversedBy: 'commandes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Compte $compte = null;

    #[ORM\OneToMany(targetEntity: CommandeDetail::class, mappedBy: 'commande')]
    private Collection $detailCommande;

    public function __construct()
    {
        $this->detailCommande = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateCommande(): ?\DateTimeInterface
    {
        return $this->dateCommande;
    }

    public function setDateCommande(\DateTimeInterface $dateCommande): static
    {
        $this->dateCommande = $dateCommande;

        return $this;
    }

    public function getCompte(): ?Compte
    {
        return $this->compte;
    }

    public function setCompte(?Compte $compte): static
    {
        $this->compte = $compte;

        return $this;
    }

    /**
     * @return Collection<int, CommandeDetail>
     */
    public function getDetailCommande(): Collection
    {
        return $this->detailCommande;
    }

    public function addDetailCommande(CommandeDetail $detailCommande): static
    {
        if (!$this->detailCommande->contains($detailCommande)) {
            $this->detailCommande->add($detailCommande);
            $detailCommande->setCommande($this);
        }

        return $this;
    }

    public function removeDetailCommande(CommandeDetail $detailCommande): static
    {
        if ($this->detailCommande->removeElement($detailCommande)) {
            // set the owning side to null (unless already changed)
            if ($detailCommande->getCommande() === $this) {
                $detailCommande->setCommande(null);
            }
        }

        return $this;
    }
}
