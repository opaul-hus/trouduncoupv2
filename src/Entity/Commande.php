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

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $Date = null;

    #[ORM\ManyToOne(inversedBy: 'commandes')]
    private ?Compte $compte = null;

    #[ORM\OneToMany(targetEntity: CommandeDetail::class, mappedBy: 'commande')]
    private Collection $commandeDetail;


    

    public function __construct()
    {
        $this->commandeDetail = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->Date;
    }

    public function setDate(\DateTimeInterface $Date): static
    {
        $this->Date = $Date;

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
    public function getCommandeDetail(): Collection
    {
        return $this->commandeDetail;
    }

    public function addCommandeDetail(CommandeDetail $commandeDetail): static
    {
        if (!$this->commandeDetail->contains($commandeDetail)) {
            $this->commandeDetail->add($commandeDetail);
            $commandeDetail->setCommande($this);
        }

        return $this;
    }

    public function removeCommandeDetail(CommandeDetail $commandeDetail): static
    {
        if ($this->commandeDetail->removeElement($commandeDetail)) {
            // set the owning side to null (unless already changed)
            if ($commandeDetail->getCommande() === $this) {
                $commandeDetail->setCommande(null);
            }
        }

        return $this;
    }

}
